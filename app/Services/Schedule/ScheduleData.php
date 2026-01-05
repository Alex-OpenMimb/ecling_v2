<?php

namespace App\Services\Schedule;

use App\Models\Schedule;

class ScheduleData
{

    public static function get_clients_headquarters( $schedule_list )
    {
        return Schedule::join('clients_has_equipments',
            'schedules.client_has_equipment_id',
            'clients_has_equipments.id')
            ->join('clients','clients_has_equipments.client_id','clients.id')
            ->whereIn('schedules.id',$schedule_list);
    }

    public static function get_status_schedules( $schedule_list )
    {
        return  Schedule::select('status')->whereIn('id',$schedule_list)->get();
    }


    public static function get_activities_equipment( $schedule_list )
    {
        return Schedule::join('clients_has_equipments',
            'schedules.client_has_equipment_id',
            'clients_has_equipments.id')
            ->join('equipments', 'clients_has_equipments.equipment_id','equipments.id')
            ->join('preventive_routines', 'schedules.preventive_routine_id','preventive_routines.id')
            ->whereIn('schedules.id',$schedule_list);
    }

    public static function get_client_by_schedule( $schedule_list )
    {
        return Schedule::join('clients_has_equipments','schedules.client_has_equipment_id','clients_has_equipments.id')
            ->join('clients', 'clients_has_equipments.client_id','clients.id')
            ->whereIn('schedules.id',$schedule_list);
    }

}
