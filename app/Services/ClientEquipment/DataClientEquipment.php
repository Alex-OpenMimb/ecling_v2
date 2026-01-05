<?php

namespace App\Services\ClientEquipment;

use App\Models\ClientsEquipments;

class DataClientEquipment
{



    public static function get_equipment( $client_equipment_id )
    {
        return ClientsEquipments::join('locations','clients_has_equipments.location_id','=','locations.id')
            ->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->join('headquarters','clients_has_equipments.headquarter_id','=','headquarters.id')
            ->join('clients','clients_has_equipments.client_id','=','clients.id')
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->join('volts','equipments.volt_id','=','volts.id')
            ->leftJoin('amperes','equipments.ampere_id','=','amperes.id')
            ->where('clients_has_equipments.id',$client_equipment_id);
    }

    public static function get_equipment_by_headquarter( $headquarter_id, $equipment_class_id )
    {
        return ClientsEquipments::join('locations','clients_has_equipments.location_id','=','locations.id')
            ->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->join('headquarters','clients_has_equipments.headquarter_id','=','headquarters.id')
            ->join('clients','clients_has_equipments.client_id','=','clients.id')
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->join('volts','equipments.volt_id','=','volts.id')
            ->leftJoin('amperes','equipments.ampere_id','=','amperes.id')
            ->where('clients_has_equipments.headquarter_id',$headquarter_id)
            ->where('equipments.equipment_class_id',$equipment_class_id);
    }


}
