<?php

namespace Database\Seeders;

use App\Helper\HandelSerial;
use App\Models\ClientsEquipments;
use App\Models\Headquarter;
use App\Services\Equipment\EquipmentService;
use App\Services\Schedule\ServicesSchedule;
use Illuminate\Database\Seeder;

class ClientEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !ClientsEquipments::get()->count() ){
            foreach (Headquarter::get() as $headquarter ){
                for ($i=0; $i < 5; $i++) {
                    $random =[1,2,3];
                    $index  = array_rand($random);
                    $equipment_class_id= $random[$index];

                    $client_equipment = ClientsEquipments::create([
                        'internal_id'=>HandelSerial::build_equipment_serial('clients_has_equipments',$equipment_class_id),
                        'preventive_services'=> 1,
                        'preventive_services_first'=> 1,
                        'schedule_assigned'=> 1,
                        'equipment_id'=> $random[$index],
                        'client_id'=> $headquarter->client_id,
                        'location_id'=> $random[$index],
                        'headquarter_id'=> $headquarter->id,
                    ]);

                    EquipmentService::asset_assign( $client_equipment->equipment_id );
                    //ServicesSchedule::create_schedule( $client_equipment );
                }

            }

        }

    }
}
