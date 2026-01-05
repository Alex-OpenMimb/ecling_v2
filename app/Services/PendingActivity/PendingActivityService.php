<?php

namespace App\Services\PendingActivity;

use App\Models\PendingActivity;

class PendingActivityService
{

    public static function create_pending_activity( $general_report )
    {
          $pending_note = $general_report->pending_note;
          $client_id_flag = $general_report->client_id;
          $headquarter_id_flag = $general_report->headquarter_id;
          $client_has_equipment_id_flag = $general_report->client_has_equipment_id;
          $equipment_class_id_flag = $general_report->equipment_class_id;
          $service_order_id_flag = $general_report->service_order_id;
          $preventive = $general_report->preventive;
          $corrective = $general_report->corrective;
          $general_report_id = $general_report->id;

          PendingActivity::create([
              'pending_note' =>$pending_note,
              'client_id_flag' => $client_id_flag,
              'headquarter_id_flag' => $headquarter_id_flag,
              'client_has_equipment_id_flag' => $client_has_equipment_id_flag,
              'equipment_class_id_flag' => $equipment_class_id_flag,
              'service_order_id_flag' => $service_order_id_flag,
              'preventive' => $preventive,
              'corrective' => $corrective,
              'general_report_id' => $general_report_id,
          ]);

    }

    public static function delete_pending_activity( $general_report )
    {
        $pending_activity_id = PendingActivity::where('general_report_id', $general_report->id)
                                             ->select('id')->firtst()->id;
        PendingActivity::where('id', $pending_activity_id)->delete();
    }


}
