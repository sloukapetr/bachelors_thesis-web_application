<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//Models
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $room = new Room;
        $room->name = 'Obývací pokoj';
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Kuchyně';
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Jídelna';
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Koupelna';
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Dětský pokoj';
        $room->floor = 2;
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Ložnice';
        $room->floor = 2;
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Koupelna 2';
        $room->floor = 2;
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);

        $room = new Room;
        $room->name = 'Garáž';
        $room->floor = 2;
        $room->save();
        \App\Models\SensorInside::factory()->count(200)->create(['room_id' => $room->id,]);
    }
}
