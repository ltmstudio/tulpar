<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TxCity;

class CitiesComponent extends Component
{
    public $name;
    public $city_edit_id;
    public $city_delete_id;
    public $city_delete_name;

    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxCity::find($edit_id);
        $this->city_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->openCreateModal();
    }

    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
        ]);

        $isEditing = $this->city_edit_id != '';
        $item = $isEditing ? TxCity::find($this->city_edit_id) : new TxCity;
        $item->name = $this->name;

        $item->save();

        if ($isEditing) {
            session()->flash('message', 'Информация о городе "' . $this->name . '" обновлена!');
        } else {
            session()->flash('message', 'Добавлен новый город "' . $this->name . '"!');
        }

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    public function deleteItem()
    {
        if ($this->city_delete_id == null) {
            return;
        }
        $itemToDelete = TxCity::find($this->city_delete_id);
        $itemToDelete->delete();
        session()->flash('message', 'Город "' . $this->city_delete_name . '" удален!');
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($i)
    {
        $itemToDelete = TxCity::find($i);
        $this->city_delete_id = $itemToDelete->id;
        $this->city_delete_name = $itemToDelete->name;
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
    }

    public function render()
    {
        $cities = TxCity::all();

        return view('livewire.cities.index', ['cities' => $cities])
            ->extends('layouts.master')
            ->section('content');
    }
}
