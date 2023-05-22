<?php

namespace App\Http\Livewire\User\Edit;

use Livewire\Component;

// Models
use App\Models\User;
use App\Models\Room;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others

class RoomAccess extends Component
{
    use AuthorizesRequests;

    public $room_permission;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($user)
    {
        $this->user = $user;
        $this->rooms = Room::all();

        foreach ($this->rooms as $room) {
            if ($this->user->rooms()->wherePivot('room_id', $room->id)->first()) {
                $this->room_permission[$room->id] = true;
            }
        }
    }

    public function submit()
    {
        if (is_array($this->room_permission)) {
            $collection = collect(array_filter($this->room_permission));
            $keys = $collection->keys()->all();
            $this->user->rooms()->sync($keys);
        } else {
            $this->user->rooms()->detach();
        }
        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.user.edit.room-access');
    }
}
