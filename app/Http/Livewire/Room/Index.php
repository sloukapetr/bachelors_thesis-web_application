<?php

namespace App\Http\Livewire\Room;

use Livewire\Component;

// Models
use App\Models\Room;
use App\Models\Setting;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others

use App\Models\Expense;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class Index extends Component
{
    use AuthorizesRequests;

    public $name;
    public $floor;

    public $modalItem;
    public $modalCreateVisible = false;
    public $modalShowVisible = false;
    public $modalUpdateVisible = false;

    public $modalConfirmDeleteVisible = false;
    public $modalConfirmRestoreVisible = false;
    public $modalConfirmForceDeleteVisible = false;

    public $modalSetTempVisible = false;

    public $setTemp;

    protected $validationAttributes = [
        'name' => 'Název místnosti',
        'floor' => 'Patro',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|max:255|unique:rooms',
            'floor' => 'sometimes|numeric|nullable',
        ];
    }

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'onPointClick' => 'handleOnPointClick',
        'onSliceClick' => 'handleOnSliceClick',
        'onColumnClick' => 'handleOnColumnClick',
        'onBlockClick' => 'handleOnBlockClick',
    ];

    public function mount()
    {
        $this->rooms = Room::withTrashed()->orderBy('floor', 'asc')->orderBy('name', 'asc')->get();
    }

    public function setValve(int $roomId)
    {
        if ($room = Room::find($roomId)) {
            if ($room->sensors()->count() > 0) {
                $actualTemp = $room->sensors()->latest()->first()->temp;
                $waterTemp = Setting::where('name', 'water_temp')->first()->value;
                $requiredWaterTemp = config('custom.required_water_temp');
                $tempDiff = $room->goal_temp - $actualTemp;
                if (($waterTemp > $requiredWaterTemp) AND ($room->goal_temp > 0)) { //Kontrola minimalni teploty a topeni zapnuto: teplota != 0
                    if ($tempDiff > 1) {
                        $valveValue = 1;
                    } elseif ($tempDiff < - 0.5) {
                        $valveValue = 0;
                    } else {
                        $valveValue = round(pow(2, ($tempDiff - 0.5)) - 0.5, 2);
                    }
                    $room->update([
                        'valve_value' => $valveValue,
                    ]);
                }
            }
        }
    }

    public function setTempShowModal($id)
    {
        $this->modalItem = Room::find($id);
        $this->authorize('setTemp', $room = $this->modalItem);
        if ($this->modalItem) {
            if (!empty($this->modalItem->goal_temp)) {
                $this->setTemp = $this->modalItem->goal_temp;
            } else {
                $this->setTemp = 21;
            }
            $this->modalSetTempVisible = true;
        }
    }

    public function setTemp()
    {
        $this->authorize('setTemp', $room = $this->modalItem);
        if ($this->modalItem) {
            $this->modalItem->update([
                'goal_temp' => $this->setTemp,
            ]);
            $this->setValve($this->modalItem->id);
            $this->modalSetTempVisible = false;
            session()->flash('temp-set');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function setHeatingOff($id)
    {
        $room = Room::find($id);
        if ($room) {
            $this->authorize('setTemp', $room);
            $room->update([
                'goal_temp' => 0,
                'valve_value' => 0,
            ]);
            session()->flash('heating-off');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
    }

    public function createShowModal()
    {
        $this->modalCreateVisible = true;
    }

    public function create()
    {
        $this->authorize('create', Room::class);
        $validatedData = $this->validate();

        if (empty($this->floor)) $this->floor = 1;

        $room = Room::create([
            'name' => $this->name,
            'floor' => $this->floor,
        ]);
        if ($room) {
            $this->name = null;
            $this->floor = null;
            $this->modalCreateVisible = false;
            session()->flash('room-created');
            //$this->emit('refreshComponent');
            $this->mount();
            $this->render();
        } else {
            abort(500);
        }
    }

    public function deleteShowModal($id)
    {
        $this->modalItem = Room::find($id);
        $this->modalConfirmDeleteVisible = true;
    }

    public function delete()
    {
        $room = $this->modalItem;
        $this->authorize('delete', $room);
        if ($room) {
            $room->delete();
            $this->modalConfirmDeleteVisible = false;
            session()->flash('room-removed');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function restoreShowModal($id)
    {
        $this->modalItem = Room::withTrashed()->find($id);
        $this->modalConfirmRestoreVisible = true;
    }

    public function restore()
    {
        $room = $this->modalItem;
        $this->authorize('restore', $room);
        if ($room) {
            $room->restore();
            $this->modalConfirmRestoreVisible = false;
            session()->flash('room-restored');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function forceDeleteShowModal($id)
    {
        $this->modalItem = Room::onlyTrashed()->find($id);
        if ($this->modalItem) {
            $this->modalConfirmForceDeleteVisible = true;
        }
    }

    public function forceDelete()
    {
        $room = $this->modalItem;
        $this->authorize('forceDelete', $room);
        if ($room) {
            $room->forceDelete();
            $this->modalConfirmForceDeleteVisible = false;
            session()->flash('room-force-deleted');
            $this->emit('refreshComponent');
        } else {
            abort(500);
        }
        $this->modalItem = null;
    }

    public function showShowModal($id)
    {
        $this->modalItem = Room::withTrashed()->find($id);
        $temperatures = $this->modalItem->sensors()->get();

        $columnChartModel =
    (LivewireCharts::columnChartModel())
        ->setTitle('Expenses by Type')
        ->addColumn('Food', 100, '#f6ad55')
        ->addColumn('Shopping', 200, '#fc8181')
        ->addColumn('Travel', 300, '#90cdf4')
    ;

        $this->modalShowVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->modalItem = Room::withTrashed()->find($id);
        $this->name = $this->modalItem->name;
        $this->floor = $this->modalItem->floor;
        $this->modalUpdateVisible = true;
    }

    public function update()
    {
        $this->authorize('update', $room = $this->modalItem);

        $validatedData = $this->validate([
            'name' => 'required|string|max:255|unique:rooms,name,'.$this->modalItem->id,
            'floor' => 'sometimes|numeric|max:255|nullable',
        ]);

        if (empty($this->floor)) $this->floor = 1;

        if ($this->modalItem) {
            $this->modalItem->update([
                'name' => $this->name,
                'floor' => $this->floor,
            ]);

            $this->name = null;
            $this->floor = null;
            $this->modalUpdateVisible = false;
            session()->flash('room-updated');
            //$this->emit('refreshComponent');
            $this->mount();
            $this->render();
        } else {
            abort(500);
        }
    }

    public function render()
    {
        $this->authorize('viewAny', Room::class);
        return view('livewire.room.index')
        ->with([
            'columnChartModel'
        ]);
    }
}
