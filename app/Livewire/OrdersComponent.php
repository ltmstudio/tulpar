<?php

namespace App\Livewire;

use App\Models\TxRideOrder;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersComponent extends Component
{

    use WithPagination;


    public function render()
    {
        $query = TxRideOrder::query();
        $items = $query->paginate(25);

        return view('livewire.orders.index', ['items' => $items])
            ->extends('layouts.master')
            ->section('content');
    }
}
