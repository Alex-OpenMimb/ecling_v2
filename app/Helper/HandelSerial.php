<?php

namespace App\Helper;

use App\Models\EquipmentClass;
use Illuminate\Support\Facades\DB;

class HandelSerial
{


    public static function build_genral_serial( $table, $prefix )
    {
        $instance = new self();
        $sequence =  $instance->get_last_serial($table, 'serial');
        return  $prefix. str_pad($sequence, 6, '0', STR_PAD_LEFT);

    }

    public static function build_equipment_serial($table, $equipment_class_id)
    {
        $instance = new self();
        $sequence =  $instance->get_last_serial($table , 'internal_id' );
        $equipment = $instance->set_equipment_class( $equipment_class_id );
        return  $equipment. str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    protected function get_last_serial( $table, $code_type )
    {
        if(DB::table($table)->count() === 0) return 1;

        $serial =  DB::table($table)->orderBy('id','desc')->get()->first();

        switch ($code_type)
        {
            case 'internal_id':
                $serial = $serial->internal_id;
                break;
            case 'serial':
                $serial =  $serial->serial;
                break;
        }

        $parts   = explode('-', $serial);
        $numeric = null;

        if(  $code_type === 'internal_id' ) {
            $numeric = intval($parts[2]);
        } else if( $code_type === 'serial' ) {
            $numeric = intval($parts[1]);
        }

        return  $numeric + 1;
    }

    /**
     * Obtiene el código de prefijo basado en el slug o nombre de EquipmentClass
     * 
     * @param int $equipment_class_id ID de la clase de equipo
     * @return string Código de prefijo (ej: 'TEHS-GAS-', 'TEHS-ELE-', etc.)
     */
    protected function set_equipment_class( $equipment_class_id )
    {
        // Consultar la clase de equipo desde la base de datos
        // find() automáticamente excluye registros con soft deletes
        $equipmentClass = EquipmentClass::find($equipment_class_id);
        
        // Si no existe la clase o está eliminada, retornar código genérico
        if (!$equipmentClass) {
            return 'TEHS-GEN-';
        }

        // Obtener el slug y nombre (normalizados a minúsculas para comparación)
        $slug = strtolower(trim($equipmentClass->slug));
        $name = strtolower(trim($equipmentClass->name));
        
        // Mapeo dinámico basado en el slug o nombre
        // Compatible con diferentes variaciones de escritura
        $codeMapping = [
            // Gas
            'gas' => 'TEHS-GAS-',
            
            // Eléctrico (con y sin tilde)
            'electrico' => 'TEHS-ELE-',
            'eléctrico' => 'TEHS-ELE-',
            'electrica' => 'TEHS-ELE-',
            'eléctrica' => 'TEHS-ELE-',
            
            // Refrigeración (con y sin tilde)
            'refrigeracion' => 'TEHS-REF-',
            'refrigeración' => 'TEHS-REF-',
        ];

        // Buscar coincidencia exacta en el slug
        if (isset($codeMapping[$slug])) {
            return $codeMapping[$slug];
        }

        // Buscar coincidencia exacta en el nombre
        if (isset($codeMapping[$name])) {
            return $codeMapping[$name];
        }

        // Buscar coincidencia parcial en el slug
        foreach ($codeMapping as $key => $code) {
            if (strpos($slug, $key) !== false) {
                return $code;
            }
        }

        // Buscar coincidencia parcial en el nombre
        foreach ($codeMapping as $key => $code) {
            if (strpos($name, $key) !== false) {
                return $code;
            }
        }

        // Si no hay coincidencia, retornar código genérico
        return 'TEHS-GEN-';
    }
}
