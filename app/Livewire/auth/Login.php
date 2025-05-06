<?php

namespace App\Livewire\auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = 'admin@stagelive.co';
    public $password = 'secret';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public function render()
    {
        // ignore the layout error shout
        return view('livewire.auth.login')->layout('layouts.authentication');
    }
    public function mount() {}

    public function store()
    {
        $attributes = $this->validate();

        $user = User::where('email', $attributes['email'])->first();

        if (!$user || !Hash::check($attributes['password'], $user->password)) {
            return $this->addError('email', 'The provided credentials are incorrect.');
        }

        if ($user->status != 1) {
            return $this->addError('email', 'Your account is not activated.');
        }

        if (auth('web')->attempt(['email' => $attributes['email'], 'password' => $attributes['password']])) {
            session()->regenerate();
            return redirect('/dashboard');
        }

        return $this->addError('email', 'An error occurred while logging in.');
    }
}
