<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//Models
use App\Models\User;

//Others
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = "Petr";
        $user->email = "petr@mail.com";
        $user->password = Hash::make('heslo');
        $user->is_admin = 1;
        $user->save();

        $user = new User;
        $user->name = "Vendula";
        $user->email = "Vendula@mail.com";
        $user->password = Hash::make('heslo');
        $user->save();

        $user = new User;
        $user->name = "Igor";
        $user->email = "igor@mail.com";
        $user->password = Hash::make('heslo');
        $user->save();

        $user = new User;
        $user->name = "Miroslav";
        $user->email = "miroslav@mail.com";
        $user->password = Hash::make('heslo');
        $user->save();

        $user = new User;
        $user->name = "Barbora";
        $user->email = "barbora@mail.com";
        $user->password = Hash::make('heslo');
        $user->save();
    }
}
