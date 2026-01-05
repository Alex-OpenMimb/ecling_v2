<?php

namespace App\Services\ServiceOrder;

use App\Helper\HandelSerial;
use App\Models\GeneralReport;
use App\Models\Schedule;
use App\Models\SchedulesServiceOrder;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderEvent;
use App\Models\ServiceOrderUser;
use App\Services\GeneralReport\GeneralReportService;
use Illuminate\Support\Facades\DB;

class ServiceOrderHandle
{
    public static function store_service_order( $data, $activities_list, $user_list )
    {

        $user_id = auth()->user()->id;
        $activity =  $data['activity'];
        $observations =  $data['observations'];
        $service_collection = [];
        foreach ( $activities_list as $index => $activity_id  ){
            $serial = HandelSerial::build_genral_serial( 'service_orders','TEHS-' );

            $service_order =  ServiceOrder::create([
                'serial'=>         $serial,
                'status'=>         'Abierta',
                'activity'=>       $activity,
                'user_id'=>        $user_id,
                'observations'=>   $observations
            ]);

            $event = $data['event'];
           // self::set_event_service_order( $service_order , $event);
            self::switch_activity( $activity, $activity_id, $service_order );
            self::set_user_by_service( $user_list, $service_order );
            $service_collection[] = $service_order;

        }

        return $service_collection;


    }



    protected static function  switch_activity( $activity, $activity_id, $service_order )
    {
        if( $activity === 'Preventiva' ){
            self::store_schedule_order( $activity_id,  $service_order );
            $data = [
                'preventive' => 1,
                'corrective' => 0,
            ]; // Status of the general report
            GeneralReportService::preventive_general_report( $service_order, $activity_id, $data );
        }elseif( $activity === 'Correctiva' ){
            self::store_corrective_order( $activity_id,  $service_order );
            $data = [
                'preventive' => 0,
                'corrective' => 1,
            ]; // Status of the general report
            GeneralReportService::corrective_general_report( $service_order, $data  );

        }
    }


    protected static function store_corrective_order( $activity_id, $service_order )
    {
        DB::table('corrective_services')
            ->where('id', $activity_id)
            ->update([
                'service_order_id'=> $service_order->id
            ]);
    }


    protected static function store_schedule_order( $activity_id, $service_order )
    {
            $client_has_equipment_id =  Schedule::where('id',$activity_id)->select('client_has_equipment_id')->first()->client_has_equipment_id;
            SchedulesServiceOrder::create([
                'service_order_id'    =>    $service_order->id,
                'schedule_id'         =>    $activity_id,
                'client_has_equipment_id' =>  $client_has_equipment_id,
            ]);
            DB::table('schedules')->where('id',$activity_id)->update(
                [
                    'service_order' => 1
                ]
            );


    }


    protected static function set_user_by_service( $user_list, $service_order )
    {
        foreach ( $user_list as $index => $user_id ){
            ServiceOrderUser::create([
                'service_order_id'=>$service_order->id,
                'user_id' => $user_id
            ]);
        }
    }

    public static function set_event_service_order( $service_order, $event )
    {
        ServiceOrderEvent::create([
            'service_order_id' => $service_order->id,
            'event_id' => $event->id
        ]);

    }

    public static  function update_service_order( $general_report )
    {
        $service_order_id = $general_report->service_order_id;
        //Checked if all general report of a service order are closed.
        $general_report_state = self::search_status_general_report( $service_order_id );
        if( $general_report_state ) {
            ServiceOrder::where('id',$service_order_id)->update([
                'status'=> 'Cerrada'
            ]);
        }
    }

    protected static function search_status_general_report( $service_order_id )
    {
        $validator = true;
        $general_reports = GeneralReport::where('service_order_id',$service_order_id)
            ->select('stored')->get();
        foreach ($general_reports as $general_report){
            if(  !$general_report->stored ){
                $validator = false;
                break;
            }
        }

        return $validator;
    }

    public static function cancel_status_report(  $service_order_id )
    {
        GeneralReport::where('service_order_id',$service_order_id)
            ->where('status','Abierto')->update([
                'status'=>'Cancelado'
            ]);
    }


}
