<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Headquarter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !Client::count() ){
            Client::factory(100)->create();
        }

        $this->create_headquarters();
    }



    public function create_addresses()
    {
        $number_main = rand(10, 1000);
       return Address::create(
                [
                    'nomenclature_main'=> 'Calle',
                    'number_main'=> $number_main,
                    'nomenclature_second'=> 'Carrera',
                    'number'     => $number_main. '-'. rand(10, 1000) .  fake()->lexify('id-????'),
                    'city_id'=>88
                ]
            );

    }

    public function create_headquarters()
    {
        $clients = Client::get();
         if( !Headquarter::count() ){
             foreach ($clients as $client )
             {
                 for ( $i= 0; $i < 10 ;  $i++ ){
                     $address = $this->create_addresses();
                     $name = fake()->company() . rand(10, 1000);
                     Headquarter::create(
                         [
                             'name'      => $name,
                             'slug'      => Str::slug($name,'-'),
                             'phone_1'   =>fake()->phoneNumber(),
                             'phone_2'   =>fake()->phoneNumber(),
                             'contact_name'=>fake()->firstNameMale() .' '.fake()->lastName(),
                             'client_id'   => $client->id,
                             'address_id'  => $address->id,
                             'email'       => Str::slug($name,'_') . '@example.com',
                             'main' => !$i ? 1:0
                         ]
                     );
                 }
             }
         }

    }
}
