<?php

namespace App\Services\ServiceOrder;

use App\Models\ServiceOrder;

class DataServiceOrder
{
    public static function get_service_order( $service_order_id  )
    {
        return ServiceOrder::where('id',$service_order_id)->select('serial')->first()->serial;
    }
}
