<?php

namespace App\Services\PreventiveRoutine;

use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineActivity;
use App\Models\PreventiveRoutineEquipment;

class ServicePreventiveRoutine
{

    public  static function createOrUpdated( $validator_data)
    {
        $equipment_list = $validator_data['equipments'];
        $activities_list = $validator_data['activities'];
        $routine_id = $validator_data['routine_id'];

        $equipment_list  = array_map('intval', $equipment_list );
        $activities_list = array_map('intval', $activities_list );

        $routines        = self::get_routines($routine_id);

        $result          =  false;
        foreach ($routines as  $routine => $routine_id){
            ///Get equipments and activities by routine id.
            $found_equipments =   self::found_equipments( $routine_id );
            $found_activities =  self::found_activities( $routine_id );

            sort( $equipment_list );
            sort( $found_equipments );
            sort( $activities_list );
            sort( $found_activities );

            if( $equipment_list === $found_equipments && $activities_list ===  $found_activities ){
                $result = $routine;
                break;
            }


        }
        return $result;
    }




    protected static function found_equipments( $equipment_id )
    {
        return  PreventiveRoutineEquipment::where('preventive_routine_id',$equipment_id)
            ->select('equipment_id')
            ->pluck('equipment_id')->toArray();
    }

    protected static function found_activities( $equipment_id )
    {
        return   PreventiveRoutineActivity::where('preventive_routine_id',$equipment_id)
            ->select('preventive_activity_id')
            ->pluck('preventive_activity_id')->toArray();

    }

    protected static function get_routines( $routine_id )
    {
        if($routine_id) return PreventiveRoutine::select('id','name')->whereNotIn('id', [$routine_id])->pluck('id','name')->toArray();
        else{
            return  PreventiveRoutine::select('id','name')->pluck('id','name')->toArray();
        }
    }

    public static function validator_preventive_routine($equipment_id)
    {
        return PreventiveRoutineEquipment::select('preventive_routines_equipments.preventive_routine_id',
            'preventive_routines_equipments.id')
            ->join('preventive_routines','preventive_routines_equipments.preventive_routine_id','=','preventive_routines.id')
            ->where('preventive_routines.status',true)
            ->where('preventive_routines_equipments.equipment_id',$equipment_id)->get()->toArray();
    }
}
