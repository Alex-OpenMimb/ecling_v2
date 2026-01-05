<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TitlePhoto;
use Illuminate\Support\Str;

class TitlePhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TitlePhoto::firstOrCreate(
            ['title' => 'Antes'],
            ['status' => 1,'slug'=>Str::slug('Antes')]
        );
    }
}
