<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Title;
#[Title('Forgot Password')]

class ForgotPasswordPage extends Component
{
    public $email;

    public function save()
    {
        // Validate the email input
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
        ]);

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Password reset link sent to your email.');
            $this->email = '';
        } else {
            session()->flash('error', 'Unable to send password reset link. Please try again later.');
        }


    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
