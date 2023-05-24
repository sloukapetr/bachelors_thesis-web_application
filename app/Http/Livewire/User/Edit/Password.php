<?php

namespace App\Http\Livewire\User\Edit;

use Livewire\Component;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Password extends Component
{

    public $state = [
        'password' => '',
        'password_confirmation' => '',
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($user)
    {
        $this->user = $user;
    }

    public function setPassword()
    {
        $this->resetErrorBag();

        $input = $this->state;

        Validator::make($input, [
            'password' => 'required|string|confirmed',
        ])->validateWithBag('updatePassword');

        $this->user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        $this->state = [
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.user.edit.password');
    }
}
