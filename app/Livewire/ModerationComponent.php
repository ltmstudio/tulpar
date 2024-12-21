<?php

namespace App\Livewire;

use App\Models\TxCarClass;
use App\Models\TxDriverModeration;
use App\Models\TxDriverProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ModerationComponent extends Component
{
    public $name;
    public $lastname;
    public $birthdate;
    public $car_name;
    public $car_model_name;
    public $car_vin;
    public $car_year;
    public $car_gos_number;
    public $car_images = array();
    public $driver_license_number;
    public $driver_license_images = array();
    public $driver_license_date;
    public $ts_passport_images = array();
    public $status;
    public $reject_message;
    public $class_id;
    public $item_edit_id;
    public $item_delete_id;
    public $item_delete_name;

    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxDriverModeration::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->lastname = $editItem->lastname;
        $this->birthdate = $editItem->birthdate ? Carbon::parse($editItem->birthdate)->format('d.m.Y') : '';
        $this->car_name = optional($editItem->car)->name;
        $this->car_model_name = optional($editItem->carModel)->name;
        $this->car_vin = $editItem->car_vin;
        $this->car_year = $editItem->car_year;
        $this->car_gos_number = $editItem->car_gos_number;
        $this->car_images = $editItem->car_images;
        $this->driver_license_number = $editItem->driver_license_number;
        $this->driver_license_images = $editItem->driver_license_images;
        $this->driver_license_date = $editItem->driver_license_date ? Carbon::parse($editItem->driver_license_date)->format('d.m.Y') : '';
        $this->ts_passport_images = $editItem->ts_passport_images;
        $this->status = $editItem->status;
        $this->reject_message = $editItem->reject_message;
        $this->openCreateModal();
    }

    public function rejectItem()
    {
        if($this->item_edit_id == null || $this->item_edit_id == ''){
            return;
        }

        $this->validate([
            'reject_message' => 'required|string',
        ]);
        

        $moderation = TxDriverModeration::find($this->item_edit_id);
        $moderation->update([
            'status' => 'rejected',
            'reject_message' => $this->reject_message
        ]);

        $moderation->save();

        session()->flash('message', 'Модерация обновлена!');

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    public function createDriverProfile()
    {
        if($this->item_edit_id == null || $this->item_edit_id == ''){
            return;
        }
        $this->validate([
            'class_id' => 'required|integer|exists:tx_car_classes,id',
        ]);
        

        $moderation = TxDriverModeration::find($this->item_edit_id);

        $driverUser = $moderation->user;
        $existingProfile = TxDriverProfile::where('phone', $driverUser->phone)->first();

        if ($existingProfile) {
            session()->flash('error', 'Профиль водителя с таким номером телефона уже существует.');
            return;
        }

        $driver = new TxDriverProfile;
        $driver->phone = $driverUser->phone;
        $driver->name = $moderation->name;
        $driver->lastname = $moderation->lastname;
        $driver->car_name = optional($moderation->car)->name . ' ' . optional($moderation->carModel)->name;
        $driver->car_number = $moderation->car_gos_number;
        $driver->class_id = $this->class_id;

        foreach ($moderation->car_images as $i => $car_image) {
            $newPath = 'public/' . $car_image;
            Storage::copy($car_image, $newPath);
            $driver->{'car_image_' . $i + 1} = $newPath;
        }

        $driver->save();

        $driverUser->role = 'DRV';
        $driverUser->driver_id = $driver->id;
        $driverUser->save();

        $moderation->status = 'approved';
        $moderation->save();


        session()->flash('message', 'Модерация обновлена! Профиль водителя создан');

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }
    
    public function createItem()
    {
        $this->validate([
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'car_name' => 'nullable|string',
            'car_model_name' => 'nullable|string',
            'car_vin' => 'nullable|string',
            'car_year' => 'nullable|string',
            'car_gos_number' => 'nullable|string',
            'driver_license_number' => 'nullable|string',
            'driver_license_front' => 'nullable|string',
            'driver_license_back' => 'nullable|string',
            'driver_license_date' => 'nullable|date',
            'ts_passport_front' => 'nullable|string',
            'ts_passport_back' => 'nullable|string',
            'status' => 'required|string',
            'reject_message' => 'nullable|string',
        ]);

        $isEditing = $this->item_edit_id != '';
        $moderation = $isEditing ? TxDriverModeration::find($this->item_edit_id) : new TxDriverModeration;
        $moderation->name = $this->name;
        $moderation->lastname = $this->lastname;
        $moderation->birthdate = $this->birthdate;
        $moderation->car_name = $this->car_name;
        $moderation->car_model_name = $this->car_model_name;
        $moderation->car_vin = $this->car_vin;
        $moderation->car_year = $this->car_year;
        $moderation->car_gos_number = $this->car_gos_number;
        $moderation->driver_license_number = $this->driver_license_number;
        $moderation->driver_license_front = $this->driver_license_front;
        $moderation->driver_license_back = $this->driver_license_back;
        $moderation->driver_license_date = $this->driver_license_date;
        $moderation->ts_passport_front = $this->ts_passport_front;
        $moderation->ts_passport_back = $this->ts_passport_back;
        $moderation->status = $this->status;
        $moderation->reject_message = $this->reject_message;

        $moderation->save();

        if ($isEditing) {
            session()->flash('message', 'Модерация обновлена!');
        } else {
            session()->flash('message', 'Новая модерация добавлена!');
        }

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    public function deleteItem()
    {
        if ($this->item_delete_id == null) {
            return;
        }
        $moderationToDelete = TxDriverModeration::find($this->item_delete_id);
        $moderationToDelete->delete();
        session()->flash('message', 'Модерация "' . $this->item_delete_name . '" удалена!');
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($id)
    {
        $moderationToDelete = TxDriverModeration::find($id);
        $this->item_delete_id = $moderationToDelete->id;
        $this->item_delete_name = $moderationToDelete->name . ' ' . $moderationToDelete->lastname;
        $this->dispatch('open-delete-modal');
    }

    public function closeDeleteModal()
    {
        $this->resetValidation();
        $this->resetInputFields();
        $this->dispatch('close-delete-modal');
    }

    public function openCreateModal()
    {
        $this->dispatch('open-create-modal');
    }

    public function closeCreateModal()
    {
        $this->resetValidation();
        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->lastname = '';
        $this->birthdate = '';
        $this->car_name = '';
        $this->car_model_name = '';
        $this->car_vin = '';
        $this->car_year = '';
        $this->car_gos_number = '';
        $this->car_images = [];
        $this->driver_license_number = '';
        $this->driver_license_images = [];
        $this->driver_license_date = '';
        $this->ts_passport_images = [];
        $this->status = '';
        $this->reject_message = '';
        $this->item_edit_id = '';
        $this->item_delete_id = '';
        $this->item_delete_name = '';
    }
    public function render()
    {
        $items = TxDriverModeration::all();
        $classes = TxCarClass::all();
        return view('livewire.moderation.index', ['items' => $items, 'classes' => $classes])
            ->extends('layouts.master')
            ->section('content');
    }
}
