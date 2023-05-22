<?php

namespace App\Http\Livewire\User\Edit;

use Livewire\Component;

// Models
use App\Models\User;
use App\Models\Room;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Illuminate\Support\Facades\Hash;
use Auth;

class Profile extends Component
{
    use AuthorizesRequests;

    public $name;
    public $email;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($user)
    {
        $this->user = $user;

        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function submit()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:128|unique:users,name,'.$this->user->id,
            'email' => 'required|string|email|max:255||unique:users,email,'.$this->user->id,
        ]);
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $this->emit('saved');
        $this->emit('refreshComponent');
        $this->emitUp('refreshComponent');
    }

    public function render()
    {
        return view('livewire.user.edit.profile');
    }
}
