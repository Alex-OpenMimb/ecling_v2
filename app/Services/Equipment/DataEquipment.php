<?php

namespace App\Services\Equipment;

use App\Models\ClientsEquipments;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class DataEquipment
{
    public static function get_equipments( $equipment_class_id )
    {
        return  DB::table('equipments')->select('equipments.id',
                   'equipments.name',
                   'equipments.asset_assignment',
                   'equipments.routine_assignment',
                    'brands.name as brand_name',
                    'volts.volt_measurement',
                    'volts.unit as volt_unit',
                    'amperes.amperage_measurement',
                    'amperes.unit as ampere_unit',
                    'equipment_models.model as equipment_model',
                   )
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->join('volts','equipments.volt_id','=','volts.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->leftJoin('amperes','equipments.ampere_id','=','amperes.id')
            ->where('equipments.equipment_class_id',$equipment_class_id)
            ->where('equipments.status',true)
            ->orderBy('brands.name')
            ->get();
    }



    public static function get_equipment_by_routine($equipment_class_id, $routine_id)
    {
        return DB::table('equipments')
            ->join('equipment_classes', 'equipments.equipment_class_id', '=', 'equipment_classes.id')
            ->join('brands', 'equipments.brand_id', '=', 'brands.id')
            ->join('volts', 'equipments.volt_id', '=', 'volts.id')
            ->join('equipment_models', 'equipments.equipment_model_id', '=', 'equipment_models.id')
            ->leftJoin('amperes', 'equipments.ampere_id', '=', 'amperes.id')
            ->where('equipments.equipment_class_id', $equipment_class_id)
            ->where('equipments.status', true)
            ->orderBy('brands.name')
            ->distinct('equipments.id')
            ->select(
                'equipments.id',
                'equipments.name',
                'equipments.asset_assignment',
                'equipments.routine_assignment',
                'brands.name as brand_name',
                'volts.volt_measurement',
                'volts.unit as volt_unit',
                'amperes.amperage_measurement',
                'amperes.unit as ampere_unit',
                'equipment_models.model as equipment_model'
            )
            ->selectRaw('
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM schedules
                    WHERE schedules.equipment_id_flag = equipments.id
                    AND schedules.preventive_routine_id = ?
                ) THEN true
                ELSE false
            END as client_has_equipment_flag
        ', [$routine_id])
            ->distinct()
            ->get();
    }



}
