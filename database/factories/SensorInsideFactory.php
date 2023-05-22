<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorInside>
 */
class SensorInsideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $created_at = Carbon::now()->subMinute(rand(1, 129600)); //43200 minut ve 1 mesici
        return [
            'temp' => rand(1800, 2200)/100,
            'humidity' => rand(5000, 8000)/100,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}


