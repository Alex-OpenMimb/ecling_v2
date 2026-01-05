<?php

namespace App\Services\ClientEquipmentCorrective;

use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;

class ClientEquipmentCorrectiveData
{

    public static function get_equipments_by_corrective( $corrective_list )
    {
        return CorrectiveService::join('clients_equipments_correctives','corrective_services.id','clients_equipments_correctives.corrective_service_id')
            ->join('clients_has_equipments','clients_equipments_correctives.client_has_equipment_id','=','clients_has_equipments.id')
            ->whereIn('corrective_services.id',$corrective_list);
    }

    public static function get_status_corrective( $corrective_list )
    {
        return  CorrectiveService::whereIn('id',$corrective_list)->select('status')->get();
    }

    public static function get_client( $corrective_list )
    {
        return  ClientsEquipmentsCorrective::select('clients.name')
            ->join('clients_has_equipments','clients_equipments_correctives.client_has_equipment_id','=','clients_has_equipments.id')
            ->join('clients','clients_has_equipments.client_id','=','clients.id')
            ->whereIn('clients_equipments_correctives.corrective_service_id',$corrective_list)->first();
    }



    public static function get_activities_equipments( $corrective_list )
    {
        return ClientsEquipmentsCorrective::join('clients_has_equipments',
            'clients_equipments_correctives.client_has_equipment_id','=','clients_has_equipments.id')
            ->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->whereIn('clients_equipments_correctives.corrective_service_id',$corrective_list);
    }


    public static function  get_order_by_activity( $corrective_list )
    {
        return  CorrectiveService::whereIn('id',$corrective_list)
            ->select('service_order_id')->pluck('service_order_id')->toArray();
    }


}
