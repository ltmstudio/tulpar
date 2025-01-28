<?php

namespace App\Livewire;

use App\Models\TxCarClass;
use App\Models\TxDriverModeration;
use App\Models\TxRideOrder;
use App\Models\User;
use Livewire\Component;

class HomeComponent extends Component
{
    public $all_periods = [0, 30, 7, 1];
    public $selected_period = 30; // 30, 7, 1, 0

    public function render()
    {
        $all_classes = TxCarClass::all();
        $result = array();

        foreach ($this->all_periods as $period) {
            $before = now()->subDays($period);
            foreach ($all_classes as $car_class) {
                $query = TxRideOrder::query();
                if ($period != 0) {
                    $query->where('updated_at', '>=', $before);
                    // dd($before);
                    $count = $query;
                    // dd($count);

                }
                $query->where('class_id', $car_class->id);
                $count = $query->count();
                // dd($count);
                $result[$period][] = ['car_class' => $car_class, "period" => $period, "count" => $count, "before" => $before];
            }
        }
        $users_total_count = User::where('driver_id', null)->count();
        $drivers_total_count = User::where('driver_id', '!=', null)->count();

        $moderations = TxDriverModeration::where('status', 'moderation')->get();
        return view('livewire.home.index', ['result' => $result, 'moderations' => $moderations, 'users_total_count' => $users_total_count, 'drivers_total_count' => $drivers_total_count])
            ->extends('layouts.master')
            ->section('content');
    }
}
