<?php

namespace App\Services\GeneralReport;

use App\Helper\HandelSerial;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\GeneralReport;
use App\Models\PreventiveRoutine;
use App\Models\Photo;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GeneralReportService
{

    public static function preventive_general_report($service_order, $activity_id, $data)
    {
        $schedule = Schedule::join('clients_has_equipments', 'schedules.client_has_equipment_id', '=', 'clients_has_equipments.id')
            ->join('equipments','clients_has_equipments.equipment_id','equipments.id')
            ->where('schedules.id', $activity_id)
            ->select('schedules.client_has_equipment_id',
                'schedules.preventive_routine_id',
                'clients_has_equipments.client_id',
                'clients_has_equipments.headquarter_id',
                'equipments.equipment_class_id',
                'schedules.id')->first();

        self::store_general_report($service_order, $data, $schedule);



    }


    public static function corrective_general_report($service_order, $data)
    {
        $corrective = ClientsEquipmentsCorrective::join('corrective_services','clients_equipments_correctives.corrective_service_id','corrective_services.id')
              ->join('clients_has_equipments', 'clients_equipments_correctives.client_has_equipment_id', '=', 'clients_has_equipments.id')
            ->join('equipments','clients_has_equipments.equipment_id','equipments.id')
            ->where('corrective_services.service_order_id', $service_order->id)
            ->distinct('clients_equipments_correctives.client_has_equipment_id')
            ->select('clients_has_equipments.id as client_has_equipment_id',
                'equipments.equipment_class_id',
                'clients_has_equipments.client_id',
                'clients_has_equipments.headquarter_id')
            ->first();

        self::store_general_report($service_order, $data, $corrective);

    }




    protected static function store_general_report($service_order, $data, $activity)
    {
        $serial = HandelSerial::build_genral_serial('general_reports', 'INFO-');
        $preventive_routine = null;
        $client_installation_id = null;
        $client_has_equipment_id = null;
        if( $data['preventive'] ){
            $preventive_routine =  PreventiveRoutine::where('id', $activity->preventive_routine_id)
                ->select('name')->first()->name;
        }

        if(  $data['corrective'] || $data['preventive'] ){
            $client_has_equipment_id   = $activity->client_has_equipment_id;
        }

        GeneralReport::create([
            'serial' => $serial,
            'preventive_routine' => $preventive_routine,
            'service_order_id' => $service_order->id,
            'preventive' => $data['preventive'],
            'corrective' => $data['corrective'],
            'client_id' => $activity->client_id,
            'headquarter_id' => $activity->headquarter_id,
            'client_has_equipment_id' => $client_has_equipment_id,
            'client_has_installation_id' => $client_installation_id,
            'equipment_class_id' =>  $activity->equipment_class_id
        ]);
    }


    public static function validate_closed_report( $service_order_id  )
    {
        $validator = true;
        $general_reports =  GeneralReport::where('service_order_id', $service_order_id)
            ->select('status')->get()->pluck('status')->toArray();
        if( in_array('Cerrado',$general_reports) )  $validator = false;
        return $validator;

    }


    public static function closed_general_report( $service_order_id )
    {
        GeneralReport::where('service_order_id',$service_order_id)
            ->update([
                'stored' => 1,
                'status' => 'Cancelado'
            ]);
    }


    public static function  update_or_stored_signature( $data )
    {
        $general_report     = $data['general_report'];
        $id                 = $data['id'];
        $signatureDataUrl   = $data['signatureDataUrl'];

        $signature_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureDataUrl));

        if (app()->environment('local')) {
            $path = 'local/signatures/general_report/';
        }else{
            $path = 'signatures/general_report/';
        }

        $signature_path = $path . $id .'/signature.png';
        $general_report->receptor_signature = $signature_path;
        $general_report->save();
        Storage::disk('space')->put($signature_path, $signature_data);
    }

    public  static function  storage_image( $data )
    {
        $general_report = $data['general_report'];
        $photoInputs    = $data['photos'] ?? [];
        $removedIds     = $data['removed'] ?? [];

        if (!empty($removedIds)) {
            Photo::whereIn('id', $removedIds)->delete();
        }

        $basePath = app()->environment('local')
            ? 'local/image/general_report/' . $general_report->id
            : 'image/general_report/' . $general_report->id;
          //dd($photoInputs);
        foreach ($photoInputs as $photoInput) {
            $flag         = $photoInput['flag'] ?? false;
            $file         = $photoInput['file'] ?? null;
            $titlePhotoId = $photoInput['title_photo_id'] ?? null;
            $titlePhotoId = $titlePhotoId !== null ? (int) $titlePhotoId : null;
            $photoId      = $photoInput['id'] ?? null;

            if (! $flag) {
                continue;
            }

            $storedPath = null;

            if ($file) {
                $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
                $filename = 'photo_' . Str::uuid() . '.' . $extension;
                $storedPath = $file->storeAs($basePath, $filename, 'space');
            }

            if ($photoId) {
                $photo = Photo::where('id', $photoId)
                    ->where('model_id', $general_report->id)
                    ->where('model_type', GeneralReport::class)
                    ->first();

                if (! $photo) {
                    continue;
                }
            } else {
                if (! $file || ! $storedPath) {
                    continue;
                }

                $photo = Photo::create([
                    'model_id'    => $general_report->id,
                    'model_type'  => GeneralReport::class,
                ]);
            }

            if ($storedPath) {
                $photo->path = $storedPath;
            }

            if ($titlePhotoId !== null) {
                $photo->title_photo_id = $titlePhotoId;
            }

            $photo->save();
        }
    }




    public static function start_time( $general_report )
    {
        $start_time = Carbon::now();
        $general_report->start_time = $start_time;
        $general_report->save();
        return Carbon::parse($general_report->start_time)->format('g:i A');
    }


    public static function end_time( $general_report  )
    {
        $start_time = $general_report->start_time;
        $start_time = Carbon::parse( $start_time );
        $end_time = Carbon::now();
        $time_spent =  $start_time->diffInMinutes( $end_time );
        $hours = floor($time_spent / 60);
        $minutes = $time_spent % 60;
        $general_report->time_spent = $hours.':'.$minutes;
        $general_report->end_time = $end_time;
        $general_report->save();

    }


}
