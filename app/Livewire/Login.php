<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public function render()
    {
        // ignore the layout error shout
        return view('livewire.login');
    }
    public function mount() {}

    public function store()
    {
        $attributes = $this->validate();

        dd($attributes);
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
