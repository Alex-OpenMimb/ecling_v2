<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create_department();
        $this->create_cities();
    }


    public  function create_department()
    {
        if( !Department::count()){
            $department_file = file_get_contents('database/data/departments.json');
            $decode_json = json_decode( $department_file );
            foreach ($decode_json as $key => $department  ){
                Department::create([
                    'name'  => $department->name,
                    'code'  => $department->id,
                    'alias'  => Str::slug($department->name,'-'),
                ]);

            }
        }

    }


    public function create_cities()
    {
       if( !City::count() ){
           $cities_file = file_get_contents('database/data/cities.json');
           $decode_json = json_decode( $cities_file );

           foreach ($decode_json as $key => $city  ){
               City::create([
                   'name'  => $city->name,
                   'code'  => $city->id,
                   'alias'  => Str::slug($city->name,'-'),
                   'department_id' => Department::where('code',$city->department_id)->first()->id
               ]);

           }
       }
    }
}
