<?php

namespace App\Http\Livewire\About;

use Livewire\Component;

// Models
use App\Models\Setting;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Illuminate\Support\Facades\Http;
use Auth;

class Index extends Component
{
    use AuthorizesRequests;

    public $modalItem;
    public $modalUpdateVisible = false;

    public $value;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->settings = Setting::all();
    }

    public function updateShowModal($id)
    {
        $this->modalItem = Setting::find($id);
        if ($this->modalItem) {
            $this->value = $this->modalItem->value;
            $this->modalUpdateVisible = true;
        }
    }

    public function update()
    {
        if ($this->modalItem) {
            $this->modalItem->update([
                'value' => $this->value,
            ]);
            $this->modalUpdateVisible = false;
            session()->flash('setting-set');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function render()
    {
        if (!empty(Auth::user())) {
            return view('livewire.about.index');
        } else {
            return view('livewire.about.index')->layout('layouts.guest');
        }
    }
}
