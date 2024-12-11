<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AccessComponent extends Component
{
    public
        $name,
        $email,
        $status,
        $password,
        $password_confirmation,
        $role;

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
        $editItem = User::find($edit_id);
        $this->item_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->email = $editItem->email;
        $this->status = $editItem->status;
        $this->role = $editItem->role;
        $this->openCreateModal();
    }


    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'status' => 'nullable|integer',
            'role' => 'required|string|in:USR,ADM,DRV,MNG',
        ]);
        
        $isEditing = $this->item_edit_id != '';
        if ($isEditing) {
            $this->validate([
                'email' => 'nullable|email|unique:users,email,' . $this->item_edit_id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);
        } else {
            $this->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
        }
        $item = $isEditing ? User::find($this->item_edit_id) : new User;
        $item->name = $this->name;
        $item->email = $this->email;
        $item->role = $this->role;
        $item->status = $this->status ?? 1;
        if ($this->password) {
            $item->password = Hash::make($this->password);
        }

        $item->save();


        if ($isEditing) {
            session()->flash('message', 'Информация о пользователе "' . $this->name . '" обновлена!');
        } else {
            session()->flash('message', 'Добавлен новый пользователь "' . $this->name . '"!');
        }

        $this->resetInputFields();

        $this->dispatch('close-create-modal');
    }

    public function deleteItem(){
        if($this->item_delete_id == null){
            return;
        }
        $userToDelete = User::find($this->item_delete_id);
        $userToDelete->delete();
        $this->resetInputFields();
        $this->closeDeleteModal();
    }


    public function render()
    {
        $items = User::where('role', 'ADM')->orWhere('role' , 'MNG')->get();
        return view('livewire.access.index', compact('items'))
            ->extends('layouts.master')
            ->section('content');
    }

    public function openDeleteModal($i)
    {
        $driverToDelete = User::find($i);
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

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->status = 1;
        $this->role = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->item_edit_id = '';
        $this->item_delete_id = '';
        $this->item_delete_name = '';
    }
}
