<?php

namespace Database\Seeders;

use App\Models\CorrectiveActivity;
use App\Models\PreventiveActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create_preventive_activities();
        $this->create_corrective_activities();
    }




    protected function create_preventive_activities()
    {
        foreach (PreventiveActivity::ACTIVITIES AS $key => $value ){
            PreventiveActivity::create([
                'activity' => $value['activity'],
                'equipment_class_id' => $value['equipment_class_id'],
            ]);
        }
    }


    protected function create_corrective_activities()
    {
        foreach (CorrectiveActivity::ACTIVITIES AS $key => $value ){
            CorrectiveActivity::create([
                'activity' => $value['activity'],
                'equipment_class_id' => $value['equipment_class_id'],
            ]);
        }

    }
}
