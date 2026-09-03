<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeerder::class);
        // $this->call(LocationSeeder::class);
        // $this->call(ClientSeeder::class);
        // $this->call(EquipmentSeeder::class);
        // $this->call(PreventiveActivitySeeder::class);
        // $this->call(PreventiveRoutineSeeder::class);
        //$this->call(ServiceSeeder::class);
        // $this->call(TitlePhotoSeeder::class);

    }
}
