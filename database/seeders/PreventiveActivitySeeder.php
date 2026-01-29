<?php

namespace Database\Seeders;

use App\Models\PreventiveActivity;
use Illuminate\Database\Seeder;

class PreventiveActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PreventiveActivity::ACTIVITIES as $item) {
            PreventiveActivity::firstOrCreate(
                [
                    'activity' => $item['activity'],
                ],
                [
                    'description' => null,
                    'status' => true,
                    'equipment_class_id' => $item['equipment_class_id'],
                ]
            );
        }
    }
}
