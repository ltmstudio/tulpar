<?php

namespace App\Livewire;

use App\Models\TxCarClass;
use App\Models\TxLevel;
use App\Models\TxShift;
use App\Models\TxShiftPrice;
use Livewire\Component;

class PricesComponent extends Component
{
    public $prices = [];



    public function savePrices()
    {
        // dd($this->prices);
        foreach ($this->prices as $carClassId => $shifts) {
            foreach ($shifts as $shiftId => $levels) {
                foreach ($levels as $levelId => $price) {
                    TxShiftPrice::updateOrCreate(
                        [
                            'tx_car_class_id' => $carClassId,
                            'tx_shift_id' => $shiftId,
                            'tx_level_id' => $levelId,
                        ],
                        ['price' => $price]
                    );
                }
            }
        }

        session()->flash('message', 'Все цены сохранены');
    }

    public function render()
    {
        $levels  = TxLevel::all();
        $car_classes = TxCarClass::all();
        $shifts = TxShift::all();
        $this->prices = TxShiftPrice::all()->groupBy(['tx_car_class_id', 'tx_shift_id', 'tx_level_id'])->map(function ($carClass) {
            return $carClass->map(function ($shift) {
            return $shift->map(function ($level) {
                return $level->first()->price;
            });
            });
        })->toArray();
        // dd(json_encode($all_prices));
        return view('livewire.prices.index', compact('levels', 'car_classes', 'shifts'))
            ->extends('layouts.master')
            ->section('content');
    }
}
