<?php

namespace App\Actions\Equipment;

use App\Models\Ampere;
use App\Models\Brand;
use App\Models\Equipment;
use App\Models\EquipmentModel;
use App\Models\Location;
use App\Models\Volt;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEquipment
{
    use AsAction;

    public function handle($data)
    {
        $brand = $this->createOrFindBrand($data['brand']);
        $location = $this->createOrFindLocation($data['location']);
        $volt = $this->createOrFindVolt((float) $data['voltage']);
        $ampere = $this->createOrFindAmpere((float) $data['amperage']);
        $equipmentModel = $this->createOrFindEquipmentModel($data['model'], $data['equipment_class_id']);

        $equipmentData = [
            'name' => $data['name'],
            'equipment_class_id' => $data['equipment_class_id'],
            'brand' => $brand,
            'volt' => $volt,
            'ampere' => $ampere,
            'equipmentModel' => $equipmentModel,
        ];

        $equipment = $this->createEquipment($equipmentData);

        return [
            'equipment' => $equipment,
            'location' => $location,
        ];
    }

    protected function createOrFindBrand(string $brandName): Brand
    {
        return Brand::firstOrCreate(
            ['name' => $brandName],
            ['status' => true]
        );
    }

    protected function createOrFindLocation(string $locationName): Location
    {
        return Location::firstOrCreate(
            ['name' => $locationName],
            ['status' => true]
        );
    }

    protected function createOrFindVolt(float $voltage): Volt
    {
        return Volt::firstOrCreate(
            ['volt_measurement' => $voltage],
            [
                'unit' => 'Voltios',
                'status' => true
            ]
        );
    }

    protected function createOrFindAmpere(float $amperage): Ampere
    {
        return Ampere::firstOrCreate(
            ['amperage_measurement' => $amperage],
            [
                'unit' => 'Amperios',
                'status' => true
            ]
        );
    }

    protected function createOrFindEquipmentModel(string $model, int $equipmentClassId): EquipmentModel
    {
        return EquipmentModel::firstOrCreate(
            [
                'model' => $model,
                'equipment_class_id' => $equipmentClassId
            ],
            ['status' => true]
        );
    }

    protected function createEquipment(array $data): Equipment
    {
        [
            'name' => $name,
            'equipment_class_id' => $equipmentClassId,
            'brand' => $brand,
            'volt' => $volt,
            'ampere' => $ampere,
            'equipmentModel' => $equipmentModel,
        ] = $data;

        return Equipment::create([
            'name' => $name,
            'slug' => Str::slug($name, '-'),
            'equipment_model_id' => $equipmentModel->id,
            'equipment_class_id' => $equipmentClassId,
            'brand_id' => $brand->id,
            'volt_id' => $volt->id,
            'ampere_id' => $ampere->id,
            'status' => true,
        ]);
    }
}
