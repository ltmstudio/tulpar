<?php

namespace App\Livewire;

use App\Models\TxLevel;
use Livewire\Component;

class DriverLevelComponent extends Component
{

    //Form Fields

    public $name,
        $count,
        $color;

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
        $editItem = TxLevel::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->count = $editItem->count;
        $this->color = $editItem->color;
        $this->openCreateModal();
    }


    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
            'count' => 'required|integer',
            'color' => 'nullable|string|max:6|min:6'
        ]);

        $isEditing = $this->item_edit_id != '';
        $item = $isEditing ? TxLevel::find($this->item_edit_id) : new TxLevel;
        $item->name = $this->name;
        $item->count = $this->count;
        $item->color = $this->color ?? 'B1B1B1';


        $item->save();


        if ($isEditing) {
            session()->flash('message', 'Информация об уровне "' . $this->name . '" обновлена!');
        } else {
            session()->flash('message', 'Добавлен новый уровень водителя "' . $this->name . '"!');
        }

        $this->resetInputFields();

        $this->dispatch('close-create-modal');
    }
    public function deleteProfile()
    {
        if ($this->item_delete_id == null) {
            return;
        }
        $itemToDelete = TxLevel::find($this->item_delete_id);
        $itemToDelete->delete();
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($i)
    {
        $itemToDelete = TxLevel::find($i);
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
        $this->count = 0;
        $this->color = '';
    }

    public function render()
    {
        $items = TxLevel::all();

        return view('livewire.driver_levels.index', ['items' => $items])
            ->extends('layouts.master')
            ->section('content');
    }
}
