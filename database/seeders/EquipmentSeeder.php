<?php

namespace Database\Seeders;

use App\Models\Ampere;
use App\Models\Brand;
use App\Models\Equipment;
use App\Models\EquipmentClass;
use App\Models\EquipmentModel;
use App\Models\Location;
use App\Models\Material;
use App\Models\SparePart;
use App\Models\Unit;
use App\Models\Volt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create_location();
        $this->create_brands();
        $this->create_equipment_classes();
        $this->create_volts();
        $this->create_ampere();
        $this->create_models();
        $this->create_equipments();
        $this->create_materials();
        $this->create_spare_parts();
        $this->create_units();
    }


    protected function create_location()
    {
        if( Location::count() === 0 ){
            foreach (Location::LOCATIONS AS $key => $value ){
                Location::create([
                    'name' =>$value,
                ]);
            }
        }

    }

    protected function create_brands()
    {
        foreach (Brand::NAME AS $key => $value ){
            Brand::create([
                'name' =>$value,
            ]);
        }
    }



    protected function create_equipment_classes()
    {
        foreach (EquipmentClass::TYPE AS $key => $value ){
            EquipmentClass::create([
                'name' =>$value,
                'slug' => Str::slug($value,'-')
            ]);
        }
    }


    protected function create_volts()
    {
        foreach (Volt::VOLT AS $key => $value ){
            Volt::create([
                'volt_measurement' =>$value,
                'unit' => 'Voltios'
            ]);
        }
    }

    protected function create_models()
    {
        foreach (EquipmentModel::MODEL AS $key => $value ){
            EquipmentModel::create([
                'model' =>  $value['model'],
                'equipment_class_id' => $value['equipment_class_id']
            ]);
        }
    }


    protected function create_ampere()
    {
        foreach (Ampere::AMPERE AS $key => $value ){
            Ampere::create([
                'amperage_measurement' =>$value,
                'unit' => 'Amperios'
            ]);
        }
    }

    protected function create_equipments()
    {
        foreach (Equipment::EQUIPMENTS AS $key => $value ){
            Equipment::create([
                'name' =>$value['name'],
                'slug' =>$value['slug'],
                'equipment_model_id' =>$value['equipment_model_id'],
                'equipment_class_id' =>$value['equipment_class_id'],
                'brand_id' =>$value['brand_id'],
                'volt_id' =>$value['volt_id'],
                'ampere_id' =>$value['ampere_id'],

            ]);
        }
    }

    protected function create_materials()
    {
        foreach (Material::MATERIAL AS $key => $value ){
            Material::create([
                'material_name' =>$value,
                'status' => 1
            ]);
        }
    }

    protected function create_spare_parts()
    {
        foreach (SparePart::SPARE AS $key => $value ){
            SparePart::create([
                'spare_part_name' =>$value,
                'status' => 1
            ]);
        }
    }


    protected function create_units()
    {
        foreach (Unit::UNITS AS $key => $value ){
            Unit::create([
                'unit_name' =>$value,
                'status' => 1
            ]);
        }
    }








}
