<?php

namespace App\Http\Livewire\User\Edit;

use Livewire\Component;

// Models
use App\Models\User;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = ['refresh'];

    public function mount($user)
    {
        $this->user = User::withTrashed()->where('id', $user)->first();
    }

    public function render()
    {
        return view('livewire.user.edit.index');
    }
}
