<?php

namespace App\Services\ClientEquipmentCorrective;

use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;
use Illuminate\Support\Facades\DB;

class ClientEquipmentCorrectiveService
{
    public static function validate_available_equipment( $equipment_list, $corrective_list, $id )
    {
        return ClientsEquipmentsCorrective::join('corrective_services','clients_equipments_correctives.corrective_service_id','corrective_services.id')
            ->select('clients_equipments_correctives.client_has_equipment_id')
            ->whereIn('clients_equipments_correctives.client_has_equipment_id',$equipment_list)
            ->whereIn('corrective_activity_id', $corrective_list)
            ->whereIn('corrective_services.status',['Abierto','Agendado','Agendado-Orden'])
            ->where('corrective_services.id', '!=',$id )
            ->get()->pluck('client_has_equipment_id')->toArray();
    }



    public static function scheduled( $activities, $service_order )
    {
        $status = $service_order ? 'Agendado-Orden' : 'Agendado';
        DB::table('corrective_services')->whereIn('id',$activities)->update([
            'status'=>  $status
        ]);

    }


    public static function delete_corrective_event( $event )
    {
        $correctives =  ClientsEquipmentsCorrective::whereIn('event_id',[$event->id])
            ->select()
            ->get();

        foreach ($correctives as $corrective  ){
            $corrective->event_id = NULL;
            $corrective->status = 'Abierta';
            $corrective->save();
        }
        $event->delete();

    }


    public static function update_status_corrective( $general_report )
    {
        $service_order_id = $general_report->service_order_id;
        CorrectiveService::where('service_order_id',$service_order_id)
            ->update([
                'status'=> 'Cerrado'
            ]);
    }

    public static function restart_status_corrective( $service_order_id )
    {
        CorrectiveService::where('service_order_id',$service_order_id)
            ->update([
                'status'=> 'Rechazado'
            ]);
    }
}
