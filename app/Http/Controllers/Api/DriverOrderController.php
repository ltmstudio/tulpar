<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TxDriverProfile;
use App\Models\TxRideOrder;
use App\Models\TxShiftOrder;
use Illuminate\Support\Facades\Auth;

class DriverOrderController extends Controller
{
    public function getNewOrders()
    {
        $user = Auth::user();
        $is_not_delivery = null;
        $is_not_cargo = null;
        $class_priority = null;

        $selected_no_cargo = request('no_cargo', false);
        $selected_no_delivery = request('no_delivery', false);

        $selected_class = request('class', null);

        if ($user->driver_id != null) {
            $driver_profile = TxDriverProfile::find($user->driver_id);
            $is_not_delivery = $driver_profile->delivery === 0 || $selected_no_delivery;
            $is_not_cargo = $driver_profile->cargo === 0  || $selected_no_cargo;
            $class_priority = optional($driver_profile->class)->priority;
        }

        // optional query parametrs like ?sorting_column=created_at&sorting_direction=desc
        $sorting_column = request('sorting_column', 'created_at');
        $sorting_direction = request('sorting_direction', 'desc');
        // optional query parametrs like ?type_id=1
        $type_id = request('type_id', null);
        $city_a_id = null;
        $city_b_id = null;

        if ($type_id != 1 && $type_id != null) {
            $city_a_id = request('city_a_id', null);
            $city_b_id = request('city_b_id', null);
        }


        $orders = TxRideOrder::where('status', 'new')
            ->where('driver_id', null)
            ->with(['cityA', 'cityB', 'class'])
            ->orderBy($sorting_column, $sorting_direction)
            ->when($type_id, function ($query, $type_id) {
                return $query->where('type_id', $type_id);
            })
            ->when($is_not_delivery, function ($query, $is_not_delivery) {
                return $query->where('is_delivery', false);
            })
            ->when($is_not_cargo, function ($query, $is_not_cargo) {
                return $query->where('is_cargo', false);
            })
            ->when($class_priority, function ($query, $class_priority) {
                return $query->where(function ($query) use ($class_priority) {
                    $query->whereHas('class', function ($query) use ($class_priority) {
                        $query->where('priority', '<=', $class_priority);
                    })->orWhereNull('class_id');
                });
            })
            ->when($city_a_id, function ($query, $city_a_id) {
                return $query->where('city_a_id', $city_a_id);
            })
            ->when($city_b_id, function ($query, $city_b_id) {
                return $query->where('city_b_id', $city_b_id);
            })
            ->when($selected_class, function ($query, $selected_class) {
                return $query->where('class_id', $selected_class);
            })
            ->get();

        $orders->each(function ($order) {
            unset($order->phone);
        });

        return response()->json($orders);
    }


    public function showOrder($id)
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        // check active shift
        $now = round(microtime(true) * 1000);
        $max = TxShiftOrder::where('driver_id', $user->driver_id)->max('endtime');

        if ($max == null || $max < $now) {
            return response()->json([
                'success' => false,
                'message' => 'Нет доступа. Проверьте активную смену'
            ], 403);
        }


        $order = TxRideOrder::where('id', $id)
            ->with(['cityA', 'cityB', 'class'])
            ->first();

        if ($order == null) {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден'
            ], 404);
        }

        return response()->json($order);
    }

    // take order
    public function takeOrder(Request $request, $id)
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        // check active shift
        $now = round(microtime(true) * 1000);
        $max = TxShiftOrder::where('driver_id', $user->driver_id)->max('endtime');

        if ($max == null || $max < $now) {
            return response()->json([
                'success' => false,
                'message' => 'Нет доступа. Проверьте активную смену'
            ], 403);
        }

        $order = TxRideOrder::where('id', $id)
            ->where('status', 'new')
            ->where('driver_id', null)
            ->first();

        if ($order == null) {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден'
            ], 404);
        }

        $order->driver_id = $user->driver_id;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Заказ принят'
        ]);
    }

    // close order
    public function closeOrder(Request $request, $id)
    {

        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        $order = TxRideOrder::where('id', $id)
            ->where('status', 'new')
            ->where('driver_id', $user->driver_id)
            ->first();

        if ($order == null) {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден'
            ], 404);
        }

        $order->status = 'closed';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Заказ закрыт'
        ]);
    }

    // cancel order
    public function cancelOrder(Request $request, $id)
    {

        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        $order = TxRideOrder::where('id', $id)
            ->where('status', 'new')
            ->where('driver_id', $user->driver_id)
            ->first();

        if ($order == null) {
            return response()->json([
                'success' => false,
                'message' => 'Заказ не найден'
            ], 404);
        }

        $order->status = 'new';
        $order->driver_id = null;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Заказ отменен'
        ]);
    }


    // my orders
    public function myOrders()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->driver->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        $orders = TxRideOrder::where('driver_id', $user->driver_id)
            ->where('status', 'new')
            ->with(['cityA', 'cityB', 'class'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($orders);
    }
    // history orders
    public function historyOrders()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        if ($user->role !== 'DRV' || $user->driver_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        $orders = TxRideOrder::where('driver_id', $user->driver_id)
            ->where('status', 'closed')
            ->with(['cityA', 'cityB', 'class'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
