<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//Models
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $setting = new Setting;
        $setting->name = 'water_temp';
        $setting->description = 'Hodnota teploty vody v topném okruhu.';
        $setting->value = '40';
        $setting->save();

    }
}
