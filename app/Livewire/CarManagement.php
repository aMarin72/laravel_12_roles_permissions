<?php

namespace App\Livewire;

use App\Models\Car;
use Livewire\Component;
use Livewire\WithPagination;

class CarManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $make;
    public $model;
    public $year;
    public $color;
    public $isEditing = false;
    public $carId;

    // rules
    protected $rules = [
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900',
        'color' => 'required|string|max:255|min:3',
    ];

    public function create()
    {
        $this->authorize('car.create');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('car.edit');
        $car = Car::findOrFail($id);
        $this->carId = $car->id;
        $this->make = $car->make;
        $this->model = $car->model;
        $this->year = $car->year;
        $this->color = $car->color;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('car.edit');
        } else {
            $this->authorize('car.create');
        }

        $this->validate();

        $carData = [
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
        ];

        if ($this->isEditing) {
            Car::findOrFail($this->carId)->update($carData);
        } else {
            Car::create($carData);
        }

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'Car saved successfully!');
    }

    public function delete($id)
    {
        $this->authorize('car.delete');
        Car::findOrFail($id)->delete();
        session()->flash('message', 'Car deleted successfully!');
    }

    public function resetForm()
    {
        $this->carId = null;
        $this->make = '';
        $this->model = '';
        $this->year = '';
        $this->color = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $this->authorize('car.view');
        return view('livewire.car-management',[
            'cars' => Car::paginate(10),
        ]);
    }
}
