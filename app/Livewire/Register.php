<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';

    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:5|confirmed',
    ];


    public function render()
    {
        return view('livewire.register')->layout('layouts.authentication');
    }

    public function store()
    {
        $attributes = $this->validate();
        $attributes['password'] = Hash::make($attributes['password']);
        $user = User::create($attributes);
        Auth::login($user);
        return redirect('/dashboard');
    }
}


// add roles -> spatie. (permissions too)
// see morph for languages / (example with products in a cart not sharing a class...)
// finalize rooms, room categories, amenities manytomany with rooms, fooditem, food category, 

// ------ Dental Clinics ------
// check DentalClinics table relationships
// Add pricing to operation types in settings.
// create seeder for all operations

// ------ Side Project ------
// Start with Inventory
