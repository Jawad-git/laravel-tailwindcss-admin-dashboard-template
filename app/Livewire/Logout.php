<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function render()
    {
        return view('livewire.logout');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/sign-in');
    }
}


// spatie role permission
//