<?php

namespace App\Services\Headquarter;

use App\Models\Headquarter;

class DataHeadquarter
{
    public static function get_address_headquarter( $id )
    {
        return  Headquarter::select('addresses.nomenclature_main','addresses.number_main','addresses.nomenclature_second',
            'addresses.number_second','addresses.number','cities.name as city_name','addresses.observations')
            ->join('addresses','headquarters.address_id','=','addresses.id')
            ->join('cities','addresses.city_id','=','cities.id')
            ->where('headquarters.id',$id)
            ->first();
    }
}
