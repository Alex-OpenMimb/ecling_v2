<?php

namespace Database\Seeders;

use App\Models\EquipmentClass;
use App\Models\PreventiveRoutine;
use Illuminate\Database\Seeder;

class PreventiveRoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = EquipmentClass::pluck('id');

        if ($classes->isEmpty()) {
            return;
        }

        $routines = [
            ['name' => 'Mantenimiento mensual', 'frequency' => 30],
            ['name' => 'Mantenimiento bimestral', 'frequency' => 60],
            ['name' => 'Mantenimiento trimestral', 'frequency' => 90],
            ['name' => 'Mantenimiento semestral', 'frequency' => 180],
            ['name' => 'Mantenimiento anual', 'frequency' => 365],
        ];

        foreach ($classes as $equipmentClassId) {
            foreach ($routines as $routine) {
                PreventiveRoutine::firstOrCreate(
                    [
                        'name' => $routine['name'],
                        'equipment_class_id' => $equipmentClassId,
                    ],
                    [
                        'frequency' => $routine['frequency'],
                        'status' => true,
                    ]
                );
            }
        }
    }
}
