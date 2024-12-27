<?php

namespace App\Livewire;

use App\Models\TxDriverBalanceLog;
use App\Models\TxCarClass;
use App\Models\TxDriverProfile;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class DriversComponent extends Component
{
    use WithPagination;
    use WithFileUploads;


    //Form Fields

    public $phone,
        $name,
        $lastname,
        $avatar,
        $avatar_select,
        $car_name,
        $car_number,
        $car_images = array(),
        $balance,
        $people = 3,
        $status,
        $delivery,
        $cargo,
        $class_id;

    public $balance_input;
    public $balance_operations = [];

    //Helpers
    public $item_edit_id,
        $item_delete_id,
        $item_delete_name;

    // Search and filters
    public $search;

    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxDriverProfile::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->lastname = $editItem->lastname;
        $this->phone = $editItem->phone;
        $this->car_name = $editItem->car_name;
        $this->car_number = $editItem->car_number;
        $this->car_images = $editItem->car_images;
        $this->status = $editItem->status;
        $this->class_id = $editItem->class_id;
        $this->delivery = $editItem->delivery;
        $this->cargo = $editItem->cargo;
        $this->avatar = $editItem->avatar ? str_replace('public/', 'storage/', $editItem->avatar) : null;
        $this->openCreateModal();
    }
    public function editBalance($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxDriverProfile::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->lastname = $editItem->lastname;
        $this->phone = $editItem->phone;
        $this->balance = $editItem->balance;
        $this->car_name = $editItem->car_name;
        $this->car_number = $editItem->car_number;
        $this->avatar = $editItem->avatar ? str_replace('public/', 'storage/', $editItem->avatar) : null;
        $this->balance_operations = $editItem->operations;
        $this->openBalanceModal();
    }


    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'avatar_select' => 'nullable|sometimes|image|max:3072',
            'phone' => 'nullable|sometimes|string|min:10|max:10',
            'car_name' => 'required|string',
            'car_number' => 'required|string',
            'status' => 'nullable|sometimes|integer|in:0,1',
            'class_id' => 'required|integer|exists:tx_car_classes,id',
            'people' => 'required|integer',
        ]);

        $isEditing = $this->item_edit_id != '';
        $item = $isEditing ? TxDriverProfile::find($this->item_edit_id) : new TxDriverProfile;
        $item->name = $this->name;
        $item->lastname = $this->lastname;
        $item->phone = $this->phone;
        $item->car_name = $this->car_name;
        $item->car_number = $this->car_number;
        $item->people = $this->people;
        $item->class_id = $this->class_id;
        $item->status = $this->status ?? 1;

        if ($this->avatar_select) {
            $old_image = $item->avatar;
            $item->avatar = $this->avatar_select->store('public/uploads/drivers');
            if ($old_image != null) {
                Storage::delete($old_image);
            }
        }

        $item->save();
        $item->syncUser();

        if ($isEditing) {
            session()->flash('message', 'Информация о водителе "' . $this->name . '" обновлена!');
        } else {
            session()->flash('message', 'Добавлен новый профиль водителя "' . $this->name . '"!');
        }

        $this->resetInputFields();

        $this->dispatch('close-create-modal');
    }
    public function deleteProfile()
    {
        if ($this->item_delete_id == null) {
            return;
        }
        $driverToDelete = TxDriverProfile::find($this->item_delete_id);
        $driverToDelete->user->update([
            'role' => 'CST',
            'driver_id' => null
        ]);
        foreach ($driverToDelete->car_images as $image) {
            Storage::delete($image);
        }
        $driverToDelete->delete();
        $this->resetInputFields();
        $this->closeDeleteModal();
    }
    public function chargeBalance()
    {
        if ($this->item_edit_id != '') {
            $this->validate([
                'balance_input' => 'required|integer',
            ]);

            $item = TxDriverProfile::find($this->item_edit_id);
            $item->balance += $this->balance_input;
            $item->save();

            $newOperation = new TxDriverBalanceLog;
            $newOperation->driver_id = $item->id;
            $newOperation->operation_value = $this->balance_input;
            $newOperation->result_balance = $item->balance;
            $newOperation->save();

            session()->flash('message-modal', 'Операция добавлена!');

            // TODO обноление баланса
            // NodeServerService::sendUpdateProfile($item->id);

            $this->resetValidation();
            $this->balance_input = 0;
            $this->balance = $item->balance;
            $this->balance_operations = $item->operations;
        }
    }

    public function setSearch() {}

    public function resetSearch()
    {
        $this->search = '';
    }

    public function render()
    {

        $query = TxDriverProfile::query();
        if ($this->search != null && $this->search != '') {
            $search = $this->search;
            $searchTerms = explode(' ', $search);
            foreach ($searchTerms as $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('name', 'like', '%' . $term . '%')
                        ->orWhere('lastname', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%');
                });
            }
        }


        $items = $query->paginate(25);

        $classes = TxCarClass::all();
        return view('livewire.drivers.index', ['items' => $items, 'classes' => $classes])
            // ->extends('layouts.master')
            // ->section('content')
            ;
    }

    public function openDeleteModal($i)
    {
        $driverToDelete = TxDriverProfile::find($i);
        $this->item_delete_id = $driverToDelete->id;
        $this->item_delete_name = $driverToDelete->name . ' ' . $driverToDelete->lastname;
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
    public function openBalanceModal()
    {
        $this->dispatch('open-balance-modal');
    }

    public function closeBalanceModal()
    {
        $this->resetValidation();
        $this->resetInputFields();
        $this->dispatch('close-balance-modal');
    }

    private function resetInputFields()
    {
        $this->phone = '';
        $this->name = '';
        $this->lastname = '';
        $this->avatar = '';
        $this->avatar_select = '';
        $this->car_name = '';
        $this->car_number = '';
        $this->car_images = [];
        $this->balance = 0;
        $this->people = 3;
        $this->balance_operations = [];
        $this->status = 1;
        $this->class_id = 1;
        $this->delivery = 0;
        $this->cargo = 0;
        $this->item_edit_id = '';
        $this->item_delete_id = '';
        $this->item_delete_name = '';
    }
}
