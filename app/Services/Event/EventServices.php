<?php

namespace App\Services\Event;

use App\Models\ClientEquipmentCorrective;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\ScheduleEvent;
use App\Models\ServiceOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventServices
{



    public static function create_event_for_order( $start_hour,$end_hour, $activity )
    {
        $date = Carbon::now()->format('Y-m-d');
        $day =  self::get_day_week( $date );
        $user_id = auth()->user()->id;
        $event =   Event::create([
            'date' =>  $date,
            'day' => $day,
            'start_hour' => $start_hour,
            'end_hour' => $end_hour,
            'activity' => $activity,
            'user_id' => $user_id,
            'service_order' => 1,
        ]);

        return $event;

    }
    public static function get_day_week($date)
    {
        $daysOfWeek = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        $dayOfWeekNumber = date('N', strtotime($date));
        return  $daysOfWeek[$dayOfWeekNumber];
    }



    public static function validate_user_date( $user_list, $date, $hours )
    {
        $user_ids = [];

        foreach ( $user_list as $index => $user_id ){
            $user = self::search_user( $user_id, $date, $hours );
            if( $user ) $user_ids[] = $user;
        }
        return User::whereIn('id',$user_ids)->select('name')->get()->toArray();
    }

    protected static function search_user( $user_id, $date, $hours)
    {
        $start_hour = $hours['start_hour'];
        $end_hour   = $hours['end_hour'];
        $event_ids = EventUser::where('user_id',$user_id)->select('event_id')->pluck('event_id')->toArray();
        $user   = NULL;
        $events = Event::whereIn('id', $event_ids)
            ->where('date', $date)
            ->where('closed', false)
            ->orderBy('start_hour')
            ->select('start_hour','end_hour')->get()->toArray();


        foreach ( $events as $event ){
            if(  $start_hour == $event['end_hour'] && $end_hour > $event['end_hour'] ){
                $user = null;
            }
            if($start_hour >= $event['start_hour'] && $end_hour <= $event['end_hour'] ){
                $user = $user_id;
            }else if( $start_hour >= $event['start_hour']  &&  $start_hour <= $event['end_hour'] && $end_hour > $event['end_hour'] ){
                $user = $user_id;
            }elseif( $start_hour < $event['start_hour'] && $end_hour >=  $event['start_hour'] ){
                $user = $user_id;
            }elseif($start_hour < $event['start_hour'] &&  $end_hour >=  $event['end_hour']){
                $user = $user_id;
            }

        }
        return $user;


    }

    public static function store_users_event( $event, $user_list )
    {
        $users = $user_list;
        foreach ( $users as $index => $user_id ){
            EventUser::create([
                'user_id' => $user_id,
                'event_id' => $event->id
            ]);
        }

    }


    public static function store_schedules( $event, $activities_list , $service_order_collection)
    {

        $activities = $activities_list;
        foreach ( $activities as $index => $activity_id ){
            $service_order_id = empty( $service_order_collection ) ? null : $service_order_collection[ $index ]->id;
            ScheduleEvent::create([
                'event_id' =>  $event->id,
                'schedule_id' => $activity_id,
                'service_order_id' => $service_order_id
            ]);
        }
    }


    public  static  function store_event_corrective( $event, $activities_list )
    {

        DB::table('corrective_services')
            ->whereIn('id',$activities_list)
            ->update([
                'event_id'=> $event->id
            ]);
    }

    public static function update_closed_event( $general_report )
    {
        $service_order_id = $general_report->service_order_id;
        $service_order    =  ServiceOrder::find( $service_order_id );
        if( $service_order->status === 'Cerrada' ) {
            self::switch_service( $service_order, 'Cerrada' );
        }

    }

    public static function close_event_by_reject( $service_order )
    {
        self::switch_service( $service_order,'Rechazada' );
    }


    /*
     * The $status_type it the type of action to execute to reject or update after filing out the report
     */
    protected static function switch_service( $service_order, $status_type  )
    {
        $activity = $service_order->activity;

        if( $activity === 'Preventiva' || $activity === 'Mixta'){
           $data =  self::update_preventive_event( $service_order );
          self::execute_update( $data, $status_type );

        }elseif( $activity === 'Correctiva'){
          $data =   self::update_corrective_event( $service_order );
            self::execute_update( $data, $status_type );

        }
    }

    protected static function update_preventive_event( $service_order )
    {
        //Get the event id
         $event = ScheduleEvent::where('service_order_id',$service_order->id)
            ->select('event_id')
            ->first();
       // Get the status of service
        $status = ScheduleEvent::join('service_orders','schedules_has_events.service_order_id','service_orders.id')
             ->where('schedules_has_events.event_id', $event->event_id)
            ->select('service_orders.status')
            ->get()->pluck('status')->toArray();
        return [
            'event'=> $event,
            'status'=> $status,
        ];

    }

    protected static function execute_update( $data, $status_type )
    {
        $status = $data['status'];
        $event = $data['event'];
        if (array_unique($status) === [$status_type]) self::update_event( $event->event_id );
    }

    protected static function update_corrective_event( $service_order )
    {
        //Get the event id
        $event = CorrectiveService::where('service_order_id', $service_order->id)
            ->select('event_id')->first();
        // Get the status of service
        $status = CorrectiveService::join('service_orders','corrective_services.service_order_id','service_orders.id')
         ->where('corrective_services.event_id', $event->event_id)
           ->select('service_orders.status')->get()->pluck('status')->toArray();
        return [
            'event'=> $event,
            'status'=> $status,
        ];

    }




    protected static function update_event( $event_id )
    {
        Event::where('id',$event_id)->update([
            'closed'  => 1
        ]);
    }


}
