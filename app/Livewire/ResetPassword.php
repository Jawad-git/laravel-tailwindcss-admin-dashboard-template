<?php

namespace App\Livewire;

use Livewire\Component;
use app\Models\User;

class ResetPassword extends Component
{
    // This reset password functionality is administered by admins,
    // and not by users changing their own passwords

    public $email = '';
    public $newPassword = '';
    public $newPassword_confirmation = '';
    public $urlID = '';

    protected $rules = [
        'email' => 'required|email',
        'newPassword' => 'required|min:8|confirmed',
    ];

    // $id is passed by the url
    public function mount($id)
    {
        $existingUser = User::find($id);
        $this->urlID = intval($existingUser->id);
    }

    public function render()
    {
        return view('livewire.reset-password');
    }

    public function update()
    {

        $this->validate();

        $existingUser = User::where('email', $this->email)->first();

        if ($existingUser && $existingUser->id == $this->urlID) {
            $existingUser->update([
                'password' => $this->password
            ]);
            redirect('sign-in')->with('status', 'Your password has been reset!');
        } else {
            return back()->with('email', "We can't find any user with that email address.");
        }
    }
}
