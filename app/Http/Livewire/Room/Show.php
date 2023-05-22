<?php

namespace App\Http\Livewire\Room;

use Livewire\Component;

// Models
use App\Models\Room;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Carbon\Carbon;

class Show extends Component
{
    use AuthorizesRequests;

    public $colors = [
        'temperature' => '#fc8181',
        'humidity' => '#90cdf4',
    ];

    public $firstRun = true;

    public $showDataLabels = false;

    protected $listeners = [
        'onPointClick' => 'handleOnPointClick',
        'onSliceClick' => 'handleOnSliceClick',
        'onColumnClick' => 'handleOnColumnClick',
        'onBlockClick' => 'handleOnBlockClick',
        'refresh',
    ];

    public function mount($room)
    {
        $this->room = Room::find($room);
    }

    public function render()
    {
        $this->authorize('viewAny', $this->room);
        if ($this->room) {
            $date_dayBefore = Carbon::now()->subDays(1);
            $date_weekBefore = Carbon::now()->subWeeks(1);
            $date_monthBefore = Carbon::now()->subMonths(1);

            $sensors_dayBefore = $this->room->sensors()->where('created_at', '>=', $date_dayBefore)->orderBy('created_at')->get();
            $sensors_weekBefore = $this->room->sensors()->where('created_at', '>=', $date_weekBefore)->orderBy('created_at')->get();
            $sensors_monthBefore = $this->room->sensors()->where('created_at', '>=', $date_monthBefore)->orderBy('created_at')->get();
            $sensors_all = $this->room->sensors()->orderBy('created_at')->get();

            $graph_dayBefore = $sensors_dayBefore->reduce(function ($graph_dayBefore, $data) use ($sensors_dayBefore) {
                    $sensor = $sensors_dayBefore->find($data);
                    return $graph_dayBefore->addPoint(Carbon::parse($data->created_at)->format('d. m. Y | H:i'), $data->temp);
                },
                LivewireCharts::lineChartModel()
                //->setTitle('sensors Evolution')
                ->setAnimated($this->firstRun)
                //->withOnPointClickEvent('onPointClick')
                ->setSmoothCurve()
                ->setXAxisVisible(true)
                ->setDataLabelsEnabled($this->showDataLabels)
                ->sparklined()
                ->withGrid()
            );

            $graph_weekBefore = $sensors_weekBefore->reduce(function ($graph_weekBefore, $data) use ($sensors_weekBefore) {
                    $sensor = $sensors_weekBefore->find($data);
                    return $graph_weekBefore->addPoint(Carbon::parse($data->created_at)->format('d. m. Y | H:i'), $data->temp);
                },
                LivewireCharts::lineChartModel()
                //->setTitle('sensors Evolution')
                ->setAnimated($this->firstRun)
                //->withOnPointClickEvent('onPointClick')
                ->setSmoothCurve()
                ->setXAxisVisible(true)
                ->setDataLabelsEnabled($this->showDataLabels)
                ->sparklined()
                ->withGrid()
            );

            $graph_monthBefore = $sensors_monthBefore->reduce(function ($graph_monthBefore, $data) use ($sensors_monthBefore) {
                    $sensor = $sensors_monthBefore->find($data);
                    return $graph_monthBefore->addPoint(Carbon::parse($data->created_at)->format('d. m. Y | H:i'), $data->temp);
                },
                LivewireCharts::lineChartModel()
                //->setTitle('sensors Evolution')
                ->setAnimated($this->firstRun)
                //->withOnPointClickEvent('onPointClick')
                ->setSmoothCurve()
                ->setXAxisVisible(true)
                ->setDataLabelsEnabled($this->showDataLabels)
                ->sparklined()
                ->withGrid()
            );

            $graph_all = $sensors_all->reduce(function ($graph_all, $data) use ($sensors_all) {
                    $sensor = $sensors_all->find($data);
                    return $graph_all->addPoint(Carbon::parse($data->created_at)->format('d. m. Y | H:i'), $data->temp);
                },
                LivewireCharts::lineChartModel()
                //->setTitle('sensors Evolution')
                ->setAnimated($this->firstRun)
                //->withOnPointClickEvent('onPointClick')
                ->setSmoothCurve()
                ->setXAxisVisible(true)
                ->setDataLabelsEnabled($this->showDataLabels)
                ->sparklined()
                ->withGrid()
            );
            return view('livewire.room.show')
                ->with([
                    'graph_dayBefore' => $graph_dayBefore,
                    'graph_weekBefore' => $graph_weekBefore,
                    'graph_monthBefore' => $graph_monthBefore,
                    'graph_all' => $graph_all,
                ]);
        } else {
            abort(404);
        }
    }
}
