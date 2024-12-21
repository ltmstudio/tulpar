<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TxCatalogCar;

class CarComponent extends Component
{
    public $name;
    public $cyrillic_name;
    public $popular;
    public $country;
    public $image;
    public $car_edit_id;
    public $car_delete_id;
    public $car_delete_name;

    // Search and filters
    public $search;
    public $search_popular = 1;

    public function addItem()
    {
        $this->resetInputFields();
        $this->openCreateModal();
    }

    public function editItem($edit_id)
    {
        $this->resetInputFields();
        $editItem = TxCatalogCar::find($edit_id);
        $this->car_edit_id = $editItem->id;
        $this->name = $editItem->name;
        $this->cyrillic_name = $editItem->{'cyrillic-name'};
        $this->popular = $editItem->popular;
        $this->country = $editItem->country;
        $this->image = $editItem->image;
        $this->openCreateModal();
    }

    public function createItem()
    {
        $this->validate([
            'name' => 'required|string',
            'cyrillic_name' => 'nullable|string',
            'popular' => 'required|boolean',
            'country' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $isEditing = $this->car_edit_id != '';
        $car = $isEditing ? TxCatalogCar::find($this->car_edit_id) : new TxCatalogCar;
        if (!$isEditing) {
            $car->id =
                strtoupper($this->name) . '_CST';
        }
        $car->name = $this->name;
        $car->{'cyrillic-name'} = $this->cyrillic_name;
        $car->popular = $this->popular;
        $car->country = $this->country;
        $car->image = $this->image;

        $car->save();

        if ($isEditing) {
            session()->flash('message', 'Автомобиль обновлен!');
        } else {
            session()->flash('message', 'Новый автомобиль добавлен!');
        }

        $this->resetInputFields();
        $this->dispatch('close-create-modal');
    }

    public function deleteItem()
    {
        if ($this->car_delete_id == null) {
            return;
        }
        $carToDelete = TxCatalogCar::find($this->car_delete_id);
        $carToDelete->delete();
        session()->flash('message', 'Автомобиль "' . $this->car_delete_name . '" удален!');
        $this->resetInputFields();
        $this->closeDeleteModal();
    }

    public function openDeleteModal($id)
    {
        $carToDelete = TxCatalogCar::find($id);
        $this->car_delete_id = $carToDelete->id;
        $this->car_delete_name = $carToDelete->name;
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
        $this->cyrillic_name = '';
        $this->popular = true;
        $this->country = '';
        $this->image = '';
        $this->car_edit_id = '';
        $this->car_delete_id = '';
        $this->car_delete_name = '';
    }

    public function setSearch() {}

    public function resetSearch()
    {
        $this->search = '';
    }

    public function render()
    {
        $cars = array();
        $query = TxCatalogCar::query();
        if ($this->search != null && $this->search != '') {
            $search = $this->search;
            $searchTerms = explode(' ', $search);
            foreach ($searchTerms as $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('name', 'like', '%' . $term . '%')
                        ->orWhere('cyrillic-name', 'like', '%' . $term . '%')
                        ->orWhereHas('models', function ($query) use ($term) {
                            $query->where('name', 'like', '%' . $term . '%')
                                ->orWhere('cyrillic-name', 'like', '%' . $term . '%');
                        });
                });
            }
        }

        if ($this->search_popular == 1 || $this->search_popular == 0) {
            $query->where('popular', $this->search_popular);
        }

        $cars = $query->get();

        return view('livewire.cars.index', ['cars' => $cars])
            ->extends('layouts.master')
            ->section('content');
    }
}
