<?php

namespace App\Services\Equipment;

use App\Models\Equipment;
use App\Services\ClientEquipment\DataClientEquipment;
use Illuminate\Support\Facades\DB;

class EquipmentService
{


    public static function asset_assign(  $equipment_id )
    {
         $equipment =  Equipment::find( $equipment_id );
         $equipment->asset_assignment = 1;
         $equipment->save();
    }
}
