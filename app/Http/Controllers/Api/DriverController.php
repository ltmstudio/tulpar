<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxDriverBalanceLog;
use App\Models\TxDriverProfile;
use App\Models\TxShiftOrder;
use App\Models\TxShiftPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role  !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Профиль водителя не найден'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Профиль водителя получен',
            'data' => $user->driver
        ]);
    }

    public function level()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role  !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Профиль водителя не найден'
            ], 404);
        }

        return response()->json($user->driver->level);
    }

    public function shiftStatus()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role  !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Профиль водителя не найден'
            ], 404);
        }

        $now = round(microtime(true) * 1000);
        $max = TxShiftOrder::where('driver_id', $user->driver_id)->max('endtime');
        $diff = $max - $now;
        $left = gmdate('H:i:s', round($diff / 1000));
        return response()->json([
            'now' => $now,
            'max' => $max,
            'diff_sec' => round($diff / 1000),
            'left' => $left,
        ]);
    }



    public function shifts()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role  !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Профиль водителя не найден'
            ], 404);
        }
        $driver = $user->driver;

        $shifts = TxShiftPrice::where(['tx_car_class_id' => $driver->class_id, 'tx_level_id' => $driver->level->id])->orderBy('tx_shift_id', 'asc')->with(['shift'])->get();
        return response()->json($shifts);
    }

    public function order(Request $request, $shift_price_id)
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role  !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Профиль водителя не найден'
            ], 404);
        }

        $driver = $user->driver;
        $driver_level = $driver->level;

        $shift_price = TxShiftPrice::find($shift_price_id);

        if (!$shift_price) {
            return response()->json([
                'success' => false,
                'message' => 'Смена не найдена'
            ], 404);
        }

        if (
            $shift_price->tx_car_class_id !== $driver->class_id ||
            $shift_price->tx_level_id !== $driver_level->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Смена не доступна'
            ], 403);
        }

        if ($driver->balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно средств'
            ], 402);
        }

        $now = round(microtime(true) * 1000);
        $shift = $shift_price->shift;
        $hours = $shift->hours;
        $endtime = $now + ($hours * 3600 * 1000);
        
        $max = TxShiftOrder::where('driver_id', $user->driver_id)->max('endtime');
        
        if($max > $now){
            $endtime = $max + ($hours * 3600 * 1000);
        }

        $new_order = new TxShiftOrder;
        $new_order->driver_id = $driver->id;
        $new_order->class_id = $shift_price->tx_car_class_id;
        $new_order->hours = $hours;
        $new_order->hours_state = $shift->state;
        $new_order->level_name = $driver_level->name;
        $new_order->price = $shift_price->price;
        $new_order->endtime = $endtime;
        $new_order->save();


        $driver->balance -= $shift_price->price;
        $driver->save();

        $newOperation = new TxDriverBalanceLog;
        $newOperation->driver_id = $driver->id;
        $newOperation->operation_value = -$shift_price->price;
        $newOperation->result_balance = $driver->balance;
        $newOperation->shift_order_id = $new_order->id;
        $newOperation->save();

        // TODO обноление баланса
        // NodeServerService::sendUpdateProfile($item->id);

        return response()->json([
            'success' => true,
            'message' => 'Смена заказана',
            'data' => $new_order
        ]);
    }
}
