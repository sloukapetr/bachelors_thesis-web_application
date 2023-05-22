<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Others
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public $weather_forecast;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function sensors(): HasMany
    {
        return $this->hasMany(SensorInside::class);
    }

    public static function boot() {
        parent::boot();

        static::forceDeleted(function($room) {
            $room->users()->detach();
            $room->sensors()->delete();
        });
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFloor()
    {
        return $this->floor.'. '.__('patro');
    }

    public function getValveValue()
    {
        return $this->valve_value;
    }

    public function getGoalTemp()
    {
        if (!empty($this->goal_temp)) {
            return $this->goal_temp. "°C";
        } else {
            return __('nenastaveno');
        }
    }

    public function getRoomTemp()
    {
        if ($this->sensors()->count() > 0) {
            //return number_format($this->sensors()->latest()->first()->temp, 1, ',', ''). "°C";
            return $this->sensors()->latest()->first()->temp. "°C";
        } else {
            return __('neměřeno');
        }
    }

    public function checkRoomTemp()
    {
        if ($this->sensors()->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getHumidity()
    {
        if ($this->sensors()->count() > 0) {
            return $this->sensors()->latest()->first()->humidity. "%";
        } else {
            return __('neměřeno');
        }
    }
}
