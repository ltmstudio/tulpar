<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TxRideOrder;

class DriverOrderController extends Controller
{
    public function getNewOrders()
    {
        $orders = TxRideOrder::where('status', 'new')
            ->with(['cityA', 'cityB', 'class'])
            ->orderBy('created_at', 'desc')
            ->get();

        $orders->each(function ($order) {
            unset($order->phone);
        });

        return response()->json($orders);
    }
}
