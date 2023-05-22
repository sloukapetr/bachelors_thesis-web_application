<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Setting;
use App\Models\SensorInside;
use App\Models\SensorOutside;
use App\Models\Room;
use App\Models\Valve;

// AuthorizesRequests
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Others
use Illuminate\Support\Facades\Http;

class HomeHeatingController extends Controller
{
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
                    return true;
                }
            }
        }
    }

    public function sensorPublish(string $appKey, string $roomId, string $temp, string $humidity)
    { //127.0.0.1:8000/sensor-publish/XYgXUjEmS1ZjwCZ5fMDQBSc3fapU07ji/4/19/54
        if (config('custom.app_key') == $appKey) {
            if ($roomId == 'outside') { /************* SENSOR OUTSIDE  ****************/
                SensorOutside::create([
                    'temp' => $temp,
                    'humidity' => $humidity,
                ]);
                http_response_code(200);
                //return "200 Data OK!";
            } else { /************* SENSOR INSIDE  ****************/
                $room = Room::find($roomId);
                if ($room) {
                    $room->sensors()->create([
                        'temp' => $temp,
                        'humidity' => $humidity,
                    ]);
                    if ($this->setValve($room->id)) {
                        http_response_code(200);
                        //return "200 Data OK!";
                    } else {
                        abort(500);
                    }
                } else {
                    abort(500);
                }
            }
        } else {
            return abort(403);
        }
    }

    public function waterTemp(string $appKey, string $temp)
    { //127.0.0.1:8000/water-temp/XYgXUjEmS1ZjwCZ5fMDQBSc3fapU07ji/40
        if (config('custom.app_key') == $appKey) {
            if ($setting = Setting::where('name', 'water_temp')->first()) {
                $setting->update([
                    'value' => $temp,
                ]);
                http_response_code(200);
            } else {
                abort(500);
            }
        } else {
            return abort(403);
        }
    }

    /*
    public function valvesSetValue(string $cronKey)
    {
        if (Setting::where('name', 'cron_key')->first()->value == $cronKey) { //Overeni cron klice
            foreach (Room::all() as $room) {
                if ($room->sensors()->count() > 0) {
                    $actualTemp = $room->sensors()->latest()->first()->temp;
                    $waterTemp = Setting::where('name', 'water_temp')->first()->value;
                    $requiredWaterTemp = Setting::where('name', 'required_water_temp')->first()->value;
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
                        echo "Room: ".$room->name." | Sensor ID: ".$room->sensor_id." | Valve value: ".$valveValue."</br>";
                        http_response_code(200);
                    } else {
                        http_response_code(599);
                    }
                } else {
                    http_response_code(599);
                }
            }
        } else {
            return abort(403);
        }
    }
    */

    public function getValveValue(string $appKey, string $roomId)
    { //127.0.0.1:8000/get-valve-value/XYgXUjEmS1ZjwCZ5fMDQBSc3fapU07ji/6
        if (config('custom.app_key') == $appKey) {
            $room = Room::find($roomId);
            if ($room) {
                http_response_code(200);
                $room->update([
                    'last_valve_value_sub' => now(),
                ]);
                return $room->getValveValue();
            } else {
                return abort(500);
            }
        } else {
            return abort(403);
        }
    }
}
