<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Component;

class BrandManagement extends Component
{
    public $showModal = false;
    public $name;
    public $isEditing = false;
    public $brandId;

    // validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function create()
    {
        $this->authorize('brand.create');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('brand.edit');
        $brand = Brand::findOrFail($id);
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('brand.edit');
        } else {
            $this->authorize('brand.create');
        }
        $this->validate();

        $brandData = [
            'name' => $this->name,
        ];

        if ($this->isEditing) {
            Brand::findOrFail($this->brandId)->update($brandData);
            session()->flash('message', 'Brand updated successfully!');
        } else {
            Brand::create($brandData);
            session()->flash('message', 'Brand created successfully!');
        }

        $this->resetForm();
        $this->showModal = false;
        // session()->flash('message', 'Brand saved successfully!');
    }

    public function delete($id)
    {
        $this->authorize('brand.delete');
        Brand::findOrFail($id)->delete();
        session()->flash('message', 'Brand deleted successfully!');
    }

    public function resetForm()
    {
        $this->brandId = null;
        $this->name = '';
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
        $this->authorize('brand.view');
        return view('livewire.brand-management',[
            'brands' => Brand::paginate(10),
        ]);
    }
}
