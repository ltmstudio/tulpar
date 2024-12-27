<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TxRideOrder;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $orders = TxRideOrder::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($orders);
    }

    public function create(Request $request)
    {

        $user = Auth::user();

        $data = $request->validate([
            'phone' => 'required|string',
            'type_id' => 'required|integer|exists:tx_ride_order_types,id',
            'class_id' => 'nullable|exists:tx_car_classes,id',
            'user_cost' => 'required|integer',
            'people' => 'nullable|integer|min:1',
            'user_comment' => 'nullable|string',
            'driver_comment' => 'nullable|string',
            'point_a' => 'nullable|string',
            'point_b' => 'nullable|string',
            'geo_a' => 'nullable|string',
            'geo_b' => 'nullable|string',
            'city_a_id' => 'nullable|exists:tx_cities,id',
            'city_b_id' => 'nullable|exists:tx_cities,id'
        ]);

        if ((!$request->filled('point_a') || !$request->filled('point_b')) && 
            (!$request->filled('city_a_id') || !$request->filled('city_b_id'))) {
            return response()->json(['message' => 'Form is not correctly filled'], 400);
        }

        $orderData = array_merge(
            $data,
            ['user_id' => $user->id]
        );

        $order = TxRideOrder::create($orderData);

        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $order = TxRideOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'driver_id' => 'required|exists:drivers,id'
        ]);

        $order->driver_id = $request->input('driver_id');
        $order->save();

        return response()->json($order, 200);
    }


    public function destroy($id)
    {
        $user = Auth::user();
        $order = TxRideOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
