<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Factories
        // \App\Models\User::factory(10)->create();
        //\App\Models\SensorOutside::factory(200)->create();

        // Seeders
        $this->call([
            //UserSeeder::class,
            //RoomSeeder::class,
            //SensorOutsideSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
