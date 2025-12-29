<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login')]

class LoginPage extends Component
{
    public $email;
    public $password;

    // Authentication logic
    public function save()
    {
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:8|max:255',
        ]);

        if(!auth()->guard()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials. Please try again.');
            return;
        }
        return redirect()->intended();

    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
