<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

// Models
use App\Models\User;
use App\Models\Room;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Illuminate\Support\Facades\Hash;

use Laravel\Fortify\Rules\Password;
use App\Actions\Fortify\PasswordValidationRules;

class Index extends Component
{
    use AuthorizesRequests;

    public $name;
    public $email;
    public $room_permission;
    public $password;
    public $password_confirmation;

    public $modalItem;
    public $modalCreateVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modalConfirmRestoreVisible = false;
    public $modalConfirmForceDeleteVisible = false;
    public $modalShowVisible = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $validationAttributes = [
        'name' => 'Jméno',
        'email' => 'E-mail',
        'room_permission.*' => 'Oprávnění do místností',
        'password' => 'Heslo',
        'password_confirmation' => 'Kontrola hesla',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'room_permission.*' => 'boolean|nullable',
            'password' => 'required|string|confirmed',
        ];
    }

    public function mount()
    {
        $this->users = User::withTrashed()->orderBy('name', 'asc')->get();
        $this->rooms = Room::all();
    }

    public function createShowModal()
    {
        $this->modalCreateVisible = true;
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $validatedData = $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        if ($user) {
            if (!empty($this->room_permission)) {
                foreach ($this->room_permission as $room_id => $value) {
                    if ($value == 1) {
                        $user->rooms()->attach($room_id);
                    }
                }
            }
            $this->name = null;
            $this->email = null;
            $this->room_permission = null;
            $this->password = null;
            $this->password_confirmation = null;
            $this->modalCreateVisible = false;
            session()->flash('user-created');
            //$this->emit('refreshComponent');
            $this->mount();
            $this->render();
        } else {
            abort(500);
        }
    }

    public function deleteShowModal($id)
    {
        $this->modalItem = User::find($id);
        $this->modalConfirmDeleteVisible = true;
    }

    public function delete()
    {
        $user = $this->modalItem;
        $this->authorize('delete', $user);
        if ($user) {
            $user->delete();
            $this->modalConfirmDeleteVisible = false;
            session()->flash('user-removed');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function restoreShowModal($id)
    {
        $this->modalItem = User::withTrashed()->find($id);
        $this->modalConfirmRestoreVisible = true;
    }

    public function restore()
    {
        $user = $this->modalItem;
        $this->authorize('restore', $user);
        if ($user) {
            $user->restore();
            $this->modalConfirmRestoreVisible = false;
            session()->flash('user-restored');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function forceDeleteShowModal($id)
    {
        $this->modalItem = User::onlyTrashed()->find($id);
        if ($this->modalItem) {
            $this->modalConfirmForceDeleteVisible = true;
        }
    }

    public function forceDelete()
    {
        $user = $this->modalItem;
        $this->authorize('forceDelete', $user);
        if ($user) {
            $user->forceDelete();
            $this->modalConfirmForceDeleteVisible = false;
            session()->flash('user-force-deleted');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function showShowModal($id)
    {
        $this->modalItem = User::withTrashed()->find($id);
        $this->modalShowVisible = true;
    }

    public function render()
    {
        $this->authorize('viewAny', User::class);
        return view('livewire.user.index');
    }
}
