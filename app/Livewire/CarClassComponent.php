<?php

namespace App\Livewire;

use App\Models\TxCarClass;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CarClassComponent extends Component
{

    use WithFileUploads;

    //Form Fields

    public $name,
        $cost,
        $priority,
        $image_select,
        $image;

    //Helpers
    public $item_edit_id,
        $item_delete_id,
        $item_delete_name;


    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxCarClass::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->cost = $editItem->cost;
        $this->priority = $editItem->priority;
        $this->image = $editItem->image ? str_replace('public/', 'storage/', $editItem->image) : null;
        $this->openCreateModal();
    }


    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
            'cost' => 'required|integer',
            'priority' => 'nullable|integer',
            'image_select' => 'nullable|sometimes|image|max:3072'
        ]);

        $isEditing = $this->item_edit_id != '';
        $item = $isEditing ? TxCarClass::find($this->item_edit_id) : new TxCarClass;
        $item->name = $this->name;
        $item->cost = $this->cost;
        $item->priority = $this->priority ?? 1;

        if ($this->image_select) {
            $old_image = $item->image;
            $item->image = $this->image_select->store('public/uploads/driverclasses');
            if ($old_image != null) {
                Storage::delete($old_image);
            }
        }

        $item->save();


        if ($isEditing) {
            session()->flash('message', 'Информация о классе "' . $this->name . '" обновлена!');
        } else {
            session()->flash('message', 'Добавлен новый класс поездки "' . $this->name . '"!');
        }

        $this->resetInputFields();

        $this->dispatch('close-create-modal');
    }
    public function deleteItem()
    {
        if ($this->item_delete_id == null) {
            return;
        }
        $itemToDelete = TxCarClass::find($this->item_delete_id);
        if ($itemToDelete->image) {
            Storage::delete($itemToDelete->image);
        }
        $itemToDelete->delete();
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($i)
    {
        $itemToDelete = TxCarClass::find($i);
        $this->item_delete_id = $itemToDelete->id;
        $this->item_delete_name = $itemToDelete->name;
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

    private function resetInputFields()
    {
        $this->name = '';
        $this->cost = 0;
        $this->priority = 0;
        $this->image = '';
        $this->image_select = '';
    }

    public function render()
    {
        $items = TxCarClass::all();

        return view('livewire.car_classes.index', ['items' => $items])
            ->extends('layouts.master')
            ->section('content');
    }
}
