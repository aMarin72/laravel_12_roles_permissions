<?php

namespace App\Livewire;

use App\Models\Car;
use App\Models\User;
use App\Models\Brand;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [];

        if (auth()->user()->can('user.view')) {
            $stats['users'] = User::count();
        }

        if (auth()->user()->can('role.view')) {
            $stats['roles'] = Role::count();
        }

        if (auth()->user()->can('car.view')) {
            $stats['cars'] = Car::count();
        }

        if (auth()->user()->can('brand.view')) {
            $stats['brands'] = Brand::count();
        }

        return view('livewire.dashboard', ['stats' => $stats]);
    }
}
