<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Title('Register')]

class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    //register user function
    public function save(){
        //validation
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
        ]);

        //create user save to database
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        //login the user
        auth()->guard()->login($user);
        //redirect to home page
        return redirect()->intended();
    }

    //

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
