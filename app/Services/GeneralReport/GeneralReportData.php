<?php

namespace App\Services\GeneralReport;

use App\Models\GeneralReport;

class GeneralReportData
{
    public static function get_client_name( $service_order_id )
    {
        return GeneralReport::join('clients','general_reports.client_id','clients.id')
            ->where('general_reports.service_order_id',$service_order_id)
            ->select('clients.name')->first()->name;
    }

    public static function get_headquarter_name( $service_order_id )
    {
        return GeneralReport::join('headquarters','general_reports.headquarter_id','headquarters.id')
            ->where('general_reports.service_order_id',$service_order_id)
            ->select('headquarters.name')->first()->name;
    }
}
