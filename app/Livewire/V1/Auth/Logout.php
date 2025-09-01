<?php

namespace App\Livewire\V1\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
        $this->redirect(route('homepage', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.v1.auth.logout');
    }
}
