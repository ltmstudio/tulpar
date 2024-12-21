<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TxShift;

class ShiftsComponent extends Component
{
    public $hours;
    public $state;
    public $shift_edit_id;
    public $shift_delete_id;
    public $shift_delete_name;

    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxShift::find($edit_id);
        $this->shift_edit_id = $editItem->id;
        $this->hours = $editItem->hours;
        $this->state = $editItem->state;
        $this->openCreateModal();
    }

    public function createItem()
    {
        $this->validate([
            'hours' => 'required|integer',
            'state' => 'required|string',
        ]);

        $isEditing = $this->shift_edit_id != '';
        $shift = $isEditing ? TxShift::find($this->shift_edit_id) : new TxShift;
        $shift->hours = $this->hours;
        $shift->state = $this->state;

        $shift->save();

        if ($isEditing) {
            session()->flash('message', 'Смена обновлена!');
        } else {
            session()->flash('message', 'Новая смена добавлена!');
        }

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    public function deleteItem()
    {
        if ($this->shift_delete_id == null) {
            return;
        }
        $shiftToDelete = TxShift::find($this->shift_delete_id);
        $shiftToDelete->delete();
        session()->flash('message', 'Смена "' . $this->shift_delete_name . '" удалена!');
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($id)
    {
        $shiftToDelete = TxShift::find($id);
        $this->shift_delete_id = $shiftToDelete->id;
        $this->shift_delete_name = $shiftToDelete->hours . ' ' . $shiftToDelete->state;
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
        $this->hours = '';
        $this->state = '';
        $this->shift_edit_id = '';
        $this->shift_delete_id = '';
        $this->shift_delete_name = '';
    }

    public function render()
    {
        $shifts = TxShift::all();

        return view('livewire.shifts.index', ['shifts' => $shifts])
            ->extends('layouts.master')
            ->section('content');
    }
}
