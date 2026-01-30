<?php

namespace App\Services\Schedule;

use App\Models\ClientsEquipments;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineEquipment;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
use App\Models\SchedulesServiceOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServicesSchedule
{
    public static function create_schedule( $client_equipment, $routine_id, $custom_frequency = null )
    {

        $id = $client_equipment->id;
        $equipment_id = $client_equipment->equipment_id;
        $routines   = self::get_routines_by_id( $routine_id );
        $now = Carbon::now()->addDays(0);
        $last_date = $now;
        foreach ( $routines as $routine ){
            $frequency = $custom_frequency ?? $routine->frequency;
            $nex_date = $now->copy()->addDays($frequency)->format('Y-m-d');
            $days = $last_date->diffInDays( $nex_date );
            $status = self::handel_status( $days );
            \App\Models\Schedule::create([
                'last_date' => $last_date,
                'next_date' => $nex_date,
                'days' => $days,
                'frequency' => $frequency,
                'status' => $status,
                'preventive_routine_id' => $routine->id,
                'client_has_equipment_id' => $id,
                'equipment_id_flag' => $equipment_id,
            ]);

        }
        $client_equipment->schedule_assigned = 1;
        $client_equipment->preventive_services_first = 1;
        $client_equipment->save();
    }

    public static function get_routines_by_id( $routine_id )
    {
        return PreventiveRoutine::where('id',$routine_id)->get();
    }

    public static function get_routines( $equipment_id )
    {
        return PreventiveRoutineEquipment::select( 'preventive_routines.id',
            'preventive_routines.frequency',
            'preventive_routines.name')
            ->join('preventive_routines','preventive_routines_equipments.preventive_routine_id','preventive_routines.id')
            ->where('preventive_routines_equipments.equipment_id',$equipment_id)->get();
    }

    public static function handel_status( $days )
    {
        $status = null;
        if( $days > 15){
            $status = 'A tiempo';
        }else if( !$days  || $days < 0  ){
            $status = 'Urgente';
        }else if( $days < 15 && $days > 0){
            $status = 'Por vencer';
        }

        return $status;
    }


    public static function handle_frequency($frequency, $last_date, $schedule  )
    {
        $status =  $schedule->status;
        $last_date = Carbon::parse($last_date);
        $current_date = Carbon::now()->addDays(0)->format('Y-m-d');
        $next_date = $last_date->copy()->addDays( $frequency );

        $days = Carbon::parse($current_date)->diffInDays( $next_date );
        //If is schedule, is not needed update the status
        if($status !== 'Agendado' ) $status = self::handel_status( $days );
        return  [
            'next_day' => $next_date,
            'days' => $days,
            'status' => $status,
        ];
    }


    public static function handle_next_date($schedule, $next_date)
    {
        $frequency = intval( $schedule->frequency );
        $next_date = Carbon::parse( $next_date );
        $last_date = $next_date->copy()->subMonths( $frequency );

        $current_date = Carbon::now()->addDays(0)->format('Y-m-d');
        $days = Carbon::parse($current_date)->diffInDays( $next_date );
        $status = self::handel_status( $days );

        $schedule->next_date = $next_date;
        $schedule->last_date = $last_date;
        $schedule->days = $days;
        $schedule->status = $status;
        $schedule->save();


    }


    public static function scheduled( $activities, $service_order )
    {
        $status = $service_order ? 'Agendada-Orden' : 'Agendada';
        DB::table('schedules')->whereIn('id',$activities)->update([
            'status'=> $status
        ]);

    }

    public static function restart_status_schedule( $service_order_id )
    {
        $schedules = SchedulesServiceOrder::join('schedules','schedules_has_service_orders.schedule_id','schedules.id')
            ->where('schedules_has_service_orders.service_order_id', $service_order_id)
            ->where('schedules.status','Agendada-Orden')
            ->select('schedules_has_service_orders.schedule_id')->get();

        foreach ($schedules as $schedule ){
            self::inactive_schedule( $schedule->schedule_id );
        }

    }

    protected  static function inactive_schedule( $schedule_id )
    {
        $schedule     = Schedule::find($schedule_id);
        $current_date = Carbon::now()->addDays(0);
        self::update_schedule( $schedule, $current_date );
    }


    public static function update_inactive_schedule( $client_equipment )
    {
        $client_equipment_id = $client_equipment->id;
        $schedule = Schedule::where('client_has_equipment_id', $client_equipment_id)->first();
        $current_date = Carbon::now()->addDays(0);
        self::update_schedule( $schedule, $current_date );
    }

    protected static function update_schedule( $schedule, $current_date )
    {
        $next_date        = Carbon::parse($schedule->next_date);
        $days             = $current_date->diffInDays( $next_date );
        $status           = self::handel_status( $days );
        $schedule->days   = $days;
        $schedule->status = $status;
        $schedule->save();
    }


    public static function delete_scheduled_event( $event )
    {
        $schedule_id =  ScheduleEvent::whereIn('event_id',[$event->id])->select('schedule_id')
            ->get()->toArray();
        self::restart_state( $schedule_id );
        $event->delete();

    }


    public static function restart_state(  $schedule_id )
    {
        $schedules = Schedule::whereIn('id',$schedule_id)->get();
        foreach ( $schedules as $schedule ){
            $next_date = $schedule->next_date;
            $next_date = Carbon::parse( $next_date );
            $current_date = Carbon::now()->addDays(0)->format('Y-m-d');
            $days = Carbon::parse($current_date)->diffInDays( $next_date );
            $status = self::handel_status( $days );
            $schedule->status = $status;
            $schedule->save();
        }
    }

    public static function update_status_schedule(  $general_report )
    {
        $service_order_id = $general_report->service_order_id;
        $client_has_equipment_id = $general_report->client_has_equipment_id;
        $schedule_id = SchedulesServiceOrder::where('service_order_id', $service_order_id)
            ->where('client_has_equipment_id', $client_has_equipment_id)
            ->select('schedule_id')->first()->schedule_id;

        Schedule::where('id', $schedule_id)->update([
            'status' => 'A tiempo',
            'service_order' => 0
        ]);
    }

    public static function update_date_schedule_report( $general_report )
    {
        $service_order_id        = $general_report->service_order_id;
        $client_has_equipment_id = $general_report->client_has_equipment_id;

        $schedule_id = self::search_schedule_id($service_order_id, $client_has_equipment_id  );
        $schedule = Schedule::find( $schedule_id );

        $las_date     = Carbon::now();
        $las_date     = $las_date->addDays(0);
        $frequency    = $schedule->frequency;
        $next_date    = $las_date->copy()->addMonths( $frequency );
        $days         = $las_date->diffInDays( $next_date );
        $status       = self::handel_status( $days );

        $schedule->last_date = $las_date->format('Y-m-d');
        $schedule->next_date = $next_date->format('Y-m-d');
        $schedule->days = $days;
        $schedule->status = $status;
        $schedule->save();


    }

    protected static function search_schedule_id( $service_order_id, $client_has_equipment_id )
    {
        return  SchedulesServiceOrder::where('service_order_id', $service_order_id)
            ->where('client_has_equipment_id',$client_has_equipment_id)
            ->select('schedule_id')->first()->schedule_id;
    }

    public static function update_schedule_by_frequency( $preventive_routine_id, $frequency )
    {
        $schedules =  Schedule::where('preventive_routine_id', $preventive_routine_id)
            ->select('last_date','id')->get();
        foreach ($schedules as $schedule  ){
            $last_date = Carbon::parse($schedule->last_date);
            $next_date = $last_date->copy()->addMonths( $frequency );
            $current_date = Carbon::now()->addDays(0)->format('Y-m-d');
            $days      = Carbon::parse($current_date)->diffInDays( $next_date );

            $status    = self::handel_status( $days );
            $schedule->next_date = $next_date;
            $schedule->frequency = $frequency;
            $schedule->days = $days;
            $schedule->status = $status;
            $schedule->save();

        }


    }

}
