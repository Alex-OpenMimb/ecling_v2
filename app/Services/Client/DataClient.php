<?php

namespace App\Services\Client;

use App\Models\Headquarter;

class DataClient
{
    public static function main_address( $id )
    {
        return  Headquarter::select('headquarters.name','headquarters.contact_name','headquarters.phone_1',
            'headquarters.phone_2','nomenclature_main','number_main','nomenclature_second',
            'number_second','number','cities.name as city_name','addresses.observations')
            ->join('addresses','headquarters.address_id','=','addresses.id')
            ->join('cities','addresses.city_id','=','cities.id')
            ->where('headquarters.client_id',$id)
            ->where('headquarters.main',true)->first();
    }
}
