<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

// Models
use App\Models\Room;
use App\Models\User;
use App\Models\Setting;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $weatherForecast;

    public function mount()
    {
        $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
        $query_str = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        //the location for the weather data (an address or partial address)
        $location = config('custom.weather_api_address');

        // the unit group - us, metric or uk
        $unitGroup = "metric";

        //we want weather data to aggregated to daily details.
        $aggregateHours=24;

        //your API key
        $apiKey = config('custom.weather_api_key');

        $api_url="https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/{$location}?unitGroup={$unitGroup}&key={$apiKey}&contentType=json";

        try {
            $response = Http::get($api_url);
            $data = $response->json();
            if (empty($data)) {
                return redirect()->route('about')->with('badWeatherAddress', 1);
            }
            $this->weatherForecast = $data;
        } catch (\Throwable $th) {
            $data = $th;
        }
    }


    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
