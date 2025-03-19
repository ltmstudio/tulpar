<?php

namespace App\Livewire;

use App\Models\TxSystemSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class PayInfoComponent extends Component
{

    use WithFileUploads;


    public $pay_link;
    public $pay_qr_image;
    public $image;
    public $pay_qr_phone;

    public function mount()
    {
        $this->pay_link = '';
        $this->pay_qr_image = '';
        $this->pay_qr_phone = '';
    }

    public function save()
    {
        $this->validate([
            'pay_link' => 'required|url',
            'image' => 'nullable|image|max:1024', // Max 1MB
            'pay_qr_phone' => 'required|string|max:15',
        ]);

        if ($this->image) {
            $new_image = $this->image->store('public/pay');
            if ($this->pay_qr_image != null) {
                Storage::delete($this->pay_qr_image);
            }
            TxSystemSetting::updateOrCreate(
                ['txkey' => 'tupar_pay_qr_image'],
                ['string_val' => $new_image]
            );
        }

        TxSystemSetting::updateOrCreate(
            ['txkey' => 'tupar_pay_link'],
            ['string_val' => $this->pay_link]
        );


        TxSystemSetting::updateOrCreate(
            ['txkey' => 'tupar_pay_qr_phone'],
            ['string_val' => $this->pay_qr_phone]
        );

        session()->flash('message', 'Payment info updated successfully.');
    }

    public function render()
    {
        $this->pay_link = TxSystemSetting::where('txkey', 'tupar_pay_link')->value('string_val') ?? '';
        $this->pay_qr_image = TxSystemSetting::where('txkey', 'tupar_pay_qr_image')->value('string_val') ?? '';
        $this->pay_qr_phone = TxSystemSetting::where('txkey', 'tupar_pay_qr_phone')->value('string_val') ?? '';

        return view('livewire.pay_info.index')
            ->extends('layouts.master')
            ->section('content');
    }
}
