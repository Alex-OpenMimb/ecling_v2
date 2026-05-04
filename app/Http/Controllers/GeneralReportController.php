<?php

namespace App\Http\Controllers;

use App\Helper\GeneralHelper;
use App\Jobs\SendEmail;
use App\Jobs\SendGeneralReportsZipEmail;
use App\Mail\GeneralReportMail;
use App\Models\Client;
use App\Models\GeneralReport;
use App\Models\GeneralReportCorrective;
use App\Models\GeneralReportMaterial;
use App\Models\GeneralReportPreventive;
use App\Models\GeneralReportSparePart;
use App\Models\Photo;
use App\Models\Headquarter;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ClientEquipment\DataClientEquipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Throwable;
use ZipArchive;
use Livewire\Livewire;

class GeneralReportController extends Controller
{

    public $general_report;
    public function viewDocument( $general_report_id )
    {
        $general_report_id     = Crypt::decryptString($general_report_id);
        $pdf = $this->create_pdf( $general_report_id );

        return  $pdf->stream();

    }

   public function send_pdf_document( $general_report )
   {
       $this->general_report   = $general_report;
       $pdf = $this->create_pdf( $this->general_report->id );

       $path = 'document/'. $this->general_report->id;
       $file_name = 'reporteGeneral.pdf';

       //Create directory if it does not exist
       if (!Storage::exists($path)) {
           Storage::makeDirectory($path);
       }
       //Save pdf file
       Storage::put($path . '/' . $file_name, $pdf->output());
       //Dispatch job to send email.
       Bus::chain([
           new SendEmail($this->general_report),
           function () use (  $path ){
               $this->general_report->sent = 'Entregado';
               $this->general_report->save();

               Storage::disk('public')->deleteDirectory($path);

           },
       ])->catch(function (Throwable $e) use ($path) {
           Log::error('Error to send email: '. $e->getMessage());
           $this->general_report->sent = 'Rechazado';
           $this->general_report->save();
           Storage::disk('public')->deleteDirectory($path);


       })->dispatch();

   }

    /**
     * Genera un PDF por cada reporte, los comprime en un ZIP y encola el envío por correo
     * siguiendo la misma cadena que {@see send_pdf_document}.
     *
     * @param  list<int>  $generalReportIds  IDs de `general_reports`; puede ser un solo elemento `[ $id ]`.
     */
    public function send_pdf_documents_zip(array $generalReportIds): void
    {
        $ids = array_values(array_unique(array_map('intval', array_filter($generalReportIds))));
        if ($ids === []) {
            throw new \InvalidArgumentException('Debe indicar al menos un id de reporte general.');
        }

        $this->general_report = GeneralReport::findOrFail($ids[0]);
        $batchId = (string) Str::uuid();
        $relativeDir = 'document/massive/'.$batchId;

        if (! Storage::exists($relativeDir)) {
            Storage::makeDirectory($relativeDir);
        }

        foreach ($ids as $generalReportId) {
            $pdf = $this->create_pdf($generalReportId);
            Storage::put($relativeDir.'/reporte_'.$generalReportId.'.pdf', $pdf->output());
        }

        $zipRelativePath = $relativeDir.'/reportes.zip';
        $this->createZipFromPdfFiles(Storage::path($relativeDir), Storage::path($zipRelativePath));

        Bus::chain([
            new SendGeneralReportsZipEmail(
                $this->general_report,
                $zipRelativePath,
                $ids
            ),
            function () use ($ids, $relativeDir) {
                foreach ($ids as $id) {
                    $report = GeneralReport::find($id);
                    if ($report) {
                        $report->sent = 'Entregado';
                        $report->save();
                    }
                }
                Storage::deleteDirectory($relativeDir);
            },
        ])->catch(function (Throwable $e) use ($relativeDir, $ids) {
            Log::error('Error to send zip email: '.$e->getMessage());
            foreach ($ids as $id) {
                $report = GeneralReport::find($id);
                if ($report) {
                    $report->sent = 'Rechazado';
                    $report->save();
                }
            }
            Storage::deleteDirectory($relativeDir);
        })->dispatch();
    }

    /**
     * Crea un archivo ZIP con todos los PDF del directorio (excluye el propio .zip si existiera).
     */
    protected function createZipFromPdfFiles(string $absoluteDirectory, string $absoluteZipPath): void
    {
        $zip = new ZipArchive;
        if ($zip->open($absoluteZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('No se pudo crear el archivo ZIP en: '.$absoluteZipPath);
        }

        foreach (File::files($absoluteDirectory) as $file) {
            if (strtolower($file->getExtension()) !== 'pdf') {
                continue;
            }
            $zip->addFile($file->getPathname(), $file->getFilename());
        }

        $zip->close();
    }

    protected  function create_pdf( $general_report_id )
    {
         $this->general_report   = GeneralReport::find($general_report_id);
        //To set the month in spanish
        setlocale(LC_TIME, 'es_ES.UTF-8');
        Carbon::setLocale('es');

        $serial_service_order  = ServiceOrder::where('id',$this->general_report->service_order_id)
                            ->select('serial')->first()->serial;

        $date            = $this->general_report->date;
        $client_id       = $this->general_report->client_id;
        $headquarter_id  = $this->general_report->headquarter_id;
        $client          = Client::where('id', $client_id)->select('name','nit')->first();
        $headquarter     = Headquarter::join('addresses','headquarters.address_id','addresses.id')
            ->join('cities','addresses.city_id','cities.id')
            ->where( 'headquarters.id', $headquarter_id )
            ->select('headquarters.name','headquarters.phone_1','addresses.nomenclature_main',
                'addresses.number_main','addresses.nomenclature_second','addresses.number_second',
                'addresses.number','cities.name as city_name')->first();
        $client_name     = $client->name;
        $headquarter_name = $headquarter->name;
        $nit             = $client->nit;
        $phone           = $headquarter->phone_1;
        $address         = $headquarter->nomenclature_main .' ' .$headquarter->number_main. ' '.
            $headquarter->nomenclature_second .' '.
            $headquarter->number_second. ' No '. $headquarter->number  .', '. $headquarter->city_name ;

        $start_hour     = $this->general_report->start_hour;
        $end_hour       = $this->general_report->end_hour;
        $operator       = $this->general_report->operator;
        $client_equipment = DataClientEquipment::get_equipment( $this->general_report->client_has_equipment_id )
            ->select(
                'brands.name as brand_name',
                'locations.name as location_name',
                'clients.name as client_name',
                'equipment_classes.name as class_name',
                'volts.volt_measurement',
                'volts.unit as volt_unit',
                'amperes.amperage_measurement',
                'amperes.unit as ampere_unit',
                'clients_has_equipments.serial',
                'clients_has_equipments.internal_id',
                'clients_has_equipments.observations',
                'equipment_models.model as equipment_model',
                'equipments.name as equipment_name'
            )->first();
        $serial           = $client_equipment->serial;
        $serial           = $serial ? ' con serial '. $serial . ', ':', ' ;
        $amperage         = $client_equipment->amperage_measurement ? ', '. $client_equipment->amperage_measurement.' '.
            $client_equipment->ampere_unit : '.';

        $client_equipment  = $client_equipment->equipment_name. ' marca '. ucfirst( strtolower( $client_equipment->brand_name ) ). ', modelo '.
            $client_equipment->equipment_model.''. $serial . 'de '. $client_equipment->volt_measurement . ' ' . $client_equipment->volt_unit
            .' '. $amperage. ', ubicada/o en el/la '. strtolower( $client_equipment->location_name ) .'.' ;

        $description_service  = $this->general_report->description_service;

        //Preventive activity
        if( $this->general_report->preventive && !$this->general_report->corrective ){
            $data =   $this->get_preventive_activities();
            $preventive_activities = implode(', ', $data);
            $description_service = 'Actividades preventivas: '. strtolower( $preventive_activities ) . '. ' .$description_service;
        }

        //Corrective activity
        if( $this->general_report->corrective &&  !$this->general_report->preventive ){
            $data = $this->get_corrective_activities();
            $corrective_activities = implode(', ', $data);
            $description_service = 'Actividades correctivas: '. strtolower( $corrective_activities ). '. ' . $description_service;

        }
        //Mix activity
        if( $this->general_report->corrective && $this->general_report->preventive  ){
            $preventive = $this->get_preventive_activities();
            $corrective = $this->get_corrective_activities();

            $preventive = implode(', ', $preventive );
            $corrective = implode(', ', $corrective );

            $description_service = 'Actividades preventivas: '. strtolower( $preventive ) . '. Actividades correctivas: '. strtolower( $corrective ). '. ' . $description_service;

        }

        $materials_spare_parts = $this->build_material_spare();
        $observations = $this->general_report->observations;
        $pending_note  = $this->general_report->pending_note;
        $receptor_signature   =  $this->general_report->receptor_signature;
        $photos = $this->buildPhotos($general_report_id);

        //Store signature
        if( $receptor_signature ){
            $directory_signature  = 'signatures/general_report/'. $general_report_id;
            $local_path = $directory_signature . '/signature.png';
            $this->store_local_image(  $receptor_signature, $local_path );
        }


        $data = [
            'date'    => Carbon::parse($date)->format('d-m-Y'),
            'serial_service_order'  => $serial_service_order,
            'client'  => $client_name,
            'headquarter_name'  => $headquarter_name,
            'nit'        => $nit,
            'phone'      => $phone,
            'address'    => $address,
            'start_hour' => Carbon::parse($start_hour)->format('g:i A'),
            'end_hour'   => Carbon::parse($end_hour)->format('g:i A'),
            'operator'   => $operator,
            'client_equipment' => $client_equipment,
            'observations' => $observations,
            'pending_note' => $pending_note,
            'receptor_signature' => $receptor_signature,
            'description_service' => $description_service,
            'preventive' => $this->general_report->preventive,
            'corrective' => $this->general_report->corrective,
            'materials_spare_parts' => $materials_spare_parts,
            'id'           => $general_report_id,
             'technic_name' => User::where('id', $this->general_report->user_id)->select('name')->first()->name,
             'receptor_name' => $this->general_report->receptor_name,
             'request_name' => $this->general_report->request_name,
             'photos' => $photos,

        ];
        return Pdf::loadView('livewire.generalReport.general_report', $data);
    }


    protected function store_local_image( $receptor_signature, $local_path )
    {
        $disk =  Storage::disk('public');
        $signature_file =   $disk->get( $receptor_signature );
        Storage::disk('public')->put($local_path, $signature_file);
    }

    protected function buildPhotos(int $general_report_id): array
    {
        $photos = $this->general_report->photos()
            ->with('titlePhoto')
            ->get();

        if ($photos->isEmpty()) {
            return [];
        }

        $isLocal = app()->environment('local');
        $localBasePath = $isLocal
            ? 'local/image/general_report/' . $general_report_id
            : 'image/general_report/' . $general_report_id;

        $result = [];

        foreach ($photos as $index => $photo) {
            if (! $photo->path) {
                continue;
            }

            $fileName = basename($photo->path) ?: 'photo_' . ($index + 1) . '.png';
            $localPath = $localBasePath . '/' . $fileName;

            $this->store_local_image($photo->path, $localPath);

            $result[] = [
                'title' => optional($photo->titlePhoto)->title,
                'path' => $localPath,
            ];
        }

        return $result;
    }

    protected function build_material_spare()
    {
        $materials = GeneralReportMaterial::join('materials','general_report_materials.material_id','materials.id')
                              ->join('units','general_report_materials.unit_id','units.id')
                              ->where( 'general_report_materials.general_report_id', $this->general_report->id )
                              ->select('materials.material_name','general_report_materials.amount as amount_m','units.unit_name')
                             ->get()
                             ->toArray();


       $spare = GeneralReportSparePart::join('spare_parts','general_report_spare_parts.spare_part_id','spare_parts.id')
                                    ->where( 'general_report_spare_parts.general_report_id', $this->general_report->id )
                                    ->select('spare_parts.spare_part_name','general_report_spare_parts.amount as amount_s')->get()->toArray();

        $materials_spare_collections = [];
        for ( $i = 0; $i < 7;$i++  ){
            $materials_spare_collections[] =[
                'material'  => $materials[ $i ]['material_name'] ?? null,
                'cant_m'    => $materials[ $i ]['amount_m'] ?? null,
                'unit'      => $materials[ $i ]['unit_name'] ?? null,
                'spare'     => $spare[ $i ]['spare_part_name'] ?? null,
                'cant_s'    => $spare[ $i ]['amount_s'] ?? null,
            ];
        }

     return $materials_spare_collections;

    }


    protected function get_preventive_activities()
    {
          return GeneralReportPreventive::join('preventive_activities','general_report_preventive.preventive_activity_id','preventive_activities.id')
                                    ->where( 'general_report_preventive.general_report_id',$this->general_report->id )
                                    ->select('preventive_activities.activity' )->get()->pluck('activity')->toArray();
    }

    protected function get_corrective_activities()
    {
        return GeneralReportCorrective::join('corrective_activities','general_report_corrective.corrective_activity_id','corrective_activities.id')
            ->where( 'general_report_corrective.general_report_id',$this->general_report->id )
            ->select('corrective_activities.activity' )->get()->pluck('activity')->toArray();
    }

}
