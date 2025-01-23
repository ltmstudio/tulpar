<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TxRideOrder;

class DriverOrderController extends Controller
{
    public function getNewOrders()
    {   // optional query parametrs like ?sorting_column=created_at&sorting_direction=desc
        $sorting_column = request('sorting_column', 'created_at');
        $sorting_direction = request('sorting_direction', 'desc');
        // optional query parametrs like ?type_id=1
        $type_id = request('type_id', null);
        
        $orders = TxRideOrder::where('status', 'new')
            ->with(['cityA', 'cityB', 'class'])
            ->orderBy($sorting_column, $sorting_direction)
            ->when($type_id, function ($query, $type_id) {
                return $query->where('type_id', $type_id);
            })
            ->get();

        $orders->each(function ($order) {
            unset($order->phone);
        });

        return response()->json($orders);
    }
}
