<?php

namespace App\Livewire\GeneralReport;

use App\Models\Client;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\GeneralReport;
use App\Models\GeneralReportCorrective;
use App\Models\GeneralReportMaterial;
use App\Models\GeneralReportPreventive;
use App\Models\GeneralReportSparePart;
use App\Models\Material;
use App\Models\PreventiveActivity;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineActivity;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderUser;
use App\Models\SparePart;
use App\Models\TitlePhoto;
use App\Models\Unit;
use App\Services\ClientEquipment\DataClientEquipment;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Event\EventServices;
use App\Services\GeneralReport\GeneralReportService;
use App\Services\PendingActivity\PendingActivityService;
use App\Services\Schedule\ServicesSchedule;
use App\Services\ServiceOrder\ServiceOrderHandle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class FormGeneralReport extends  Component
{
    use WithFileUploads;
    #[Locked]
    public $id;

    public GeneralReport $general_report;

    public $client_name, $service_order_id,$serial, $operator,$preventive,$corrective;
    public $client_equipment_id, $equipment_name, $location, $brand, $equipment_model;
    public $equipment_serial, $volt_measurement, $amperage_measurement,$volt_unit,$ampere_unit;
    public $preventive_routine,$preventive_activities,$corrective_activities, $used_spare_parts;
    public $used_materials, $units, $description_service,$observations,$pending_note, $end_hour, $start_hour;
    public $date,$preventive_activities_check = [],$spare_part_check = [], $spare_part_input = [];
    public $material_inputs = [], $material_select = [], $materials_check = [];
    public $photoInputs = [];
    public $removedPhotoIds = [];
    public $titlePhotoOptions = [];
    public $receptor_signature,$stored,$pending;
    public $receptor_name,$receptor_document,$receptor_document_type,$receptor_position;
    public $corrective_activities_check = [], $start_time, $end_time, $time_spent, $request_name, $signatureDataUrl;
    public $signature_flag;


    public function mount( $service_order_id, $general_report_id )
    {

        $this->set_general_data($service_order_id, $general_report_id);
        $this->set_equipment_data();
        $this->set_activities();
        $this->used_spare_parts = SparePart::getSpareParts()->get();
        $this->set_input_spare();
        $this->used_materials = Material::getMaterials()->get();
        $this->set_materials_inputs();
        $this->units = Unit::getUnits()->get();

        if ($this->stored){
            $this->fill(
                $this->general_report->only(
                    'date','start_hour','end_hour','operator','description_service',
                    'observations','pending_note', 'receptor_name',
                    'receptor_signature',
                    'receptor_document',
                    'receptor_document_type',
                    'receptor_position',
                   'pending','time_spent','request_name')
            );

            if( $this->preventive ) $this->get_preventive_activities();
            if( $this->corrective ) $this->get_corrective_activities();
            $this->get_spare_parts();
            $this->get_materials();
        }

        $this->titlePhotoOptions = TitlePhoto::where('status', 1)
            ->orderBy('title')
            ->get(['id','title']);

        $this->initializePhotoInputs();

    }

    public function render()
    {
        return view('livewire.generalReport.form');
    }


    public function  updateOrStore()
    {
            $this->validate();

            if( $this->end_hour < $this->start_hour ) return toastr()->error('La hora final debe ser mayor a la inicial.','Error');
            if( $this->end_hour == $this->start_hour ) return toastr()->error('La hora inicial debe ser diferente  a la hora final.','Error');
            if( empty($this->preventive_activities_check) && $this->preventive) return  toastr()->error('Selecciona al menos una actividad preventiva.','Error');
            if( empty($this->corrective_activities_check) && $this->corrective) return  toastr()->error('Selecciona al menos una actividad correctiva.','Error');

            //if( empty($this->spare_part_check) ) return  toastr()->error('Selecciona al menos un repuesto.','Error');
            //if( empty($this->materials_check) ) return  toastr()->error('Selecciona al menos un material.','Error');
            if( count( $this->materials_check ) > 7) return toastr()->error('Selecciona máximo 7 materiales','Error');
            if( count( $this->spare_part_check ) > 7) return toastr()->error('Selecciona máximo 7 repuestos','Error');

            $spare = $this->validate_spare_check();
            if( !$spare ) return toastr()->error('No ha seleccionado un repuesto','Error');
            $material = $this->validte_material_check();
            $validator_material = $this->validate_material_check_select();
            if( !$material || !$validator_material ) return toastr()->error('No ha seleccionado un material','Error');
            $spare_input = $this->validate_spare_parts_input();
            if( !$spare_input ) return toastr()->error('No ha ingresado un valor válido en las cantidades de los respuestos','Error');
            $material_input = $this->validate_material_input();
            if( !$material_input ) return toastr()->error('No ha ingresado un valor válido en las cantidades de los materiales','Error');
            $material_select = $this->validate_material_select();
            if( !$material_select ) return toastr()->error('No ha seleccionado un valor válido en la unidad de los materiales','Error');
            if( $this->pending && !$this->pending_note) return toastr()->error('Describe las actividades pendientes para el equipo en el cuadro de "Pendientes".', 'Error');
            if( !$this->signatureDataUrl && $this->signature_flag) return toastr()->error('Estamos procesando tu firma, por favor espera un momento.','Info');


            $this->validatePhotos();

            $data =    $this->build_data();
            $find_id = [
                'id'=>$this->id
            ];
            DB::beginTransaction();
            if( $this->general_report->start_time && !$this->time_spent  ) GeneralReportService::end_time( $this->general_report );

            if( $this->stored ){
                //Edit
                if( $this->general_report->pending && !$this->pending ) {
                    return toastr()->error('Estás intentando editar un reporte desmarcando el cheklist de  actividades pendientes, pero fue creado con una. Para rechazar la actividad pendiente,
                     por favor dirígete al módulo correspondiente.');
                }

                GeneralReportSparePart::where('general_report_id', $this->general_report->id)->delete();
                GeneralReportMaterial::where('general_report_id', $this->general_report->id)->delete();
                if( $this->preventive ){
                    GeneralReportPreventive::where('general_report_id', $this->general_report->id)->delete();
                    $this->store_preventive_activity();
                }
                if( $this->corrective ){
                    GeneralReportCorrective::where('general_report_id', $this->general_report->id)->delete();
                    $this->store_corrective_activities();
                }

            }else{
                //Created
                $storedTime = Carbon::now();
                $this->general_report->stored_time = $storedTime;
                $this->general_report->save();
                if( $this->preventive ){
                    $this->store_preventive_activity();
                    ServicesSchedule::update_status_schedule( $this->general_report );
                    ServicesSchedule::update_date_schedule_report( $this->general_report );

                }
                if( $this->corrective ){
                    $this->store_corrective_activities();
                    ClientEquipmentCorrectiveService::update_status_corrective( $this->general_report );

                }
            }
            $this->store_spare_part();
            $this->store_materials();



            $general_report =  GeneralReport::updateOrCreate( $find_id, $data );
            $this->storage_image();

            if( !$this->stored ){
                if( $this->pending ) PendingActivityService::create_pending_activity( $general_report );
                ServiceOrderHandle::update_service_order( $this->general_report );
                EventServices::update_closed_event( $this->general_report );
              }else{
                if( !$this->general_report->pending && $this->pending ) PendingActivityService::create_pending_activity( $general_report );
            }
            $message = !$this->id ? 'creado':'actualizado';
            toastr()->success('El formularioo se ha '. $message .' con éxito!', 'Felicitaciones');
            DB::commit();
            if( $this->signatureDataUrl ) $this->store_signature();
            return redirect()->route('admin.general-reports',['service_order_id'=>$this->service_order_id]);


    }

    protected function build_data()
    {
        $user_id = auth()->user()->id;

        $description_service = trim($this->description_service);
        $observations        = trim( $this->observations );
        $this->pending_note  = trim($this->pending_note) ?: null;
        if( !$this->pending && $this->pending_note ) $this->pending = true;
        return [
            'date'               => $this->date,
            'start_hour'         => $this->start_hour,
            'end_hour'           => $this->end_hour,
            'operator'           => $this->operator,
            'description_service'=> $description_service,
            'observations'       => $observations,
            'pending_note'       => $this->pending_note,
            'pending'            => (bool)$this->pending,
            'receptor_name'      => $this->receptor_name,
            'request_name'       => $this->request_name,
            'receptor_document'     => $this->receptor_document,
            'receptor_document_type'=> $this->receptor_document_type,
            'receptor_position'     => $this->receptor_position,
            'user_id'=> $user_id,
            'stored' => 1,
            'status' => 'Cerrado',
        ];
    }


    protected function set_general_data( $service_order_id, $general_report_id )
    {
        $this->service_order_id = $service_order_id;
        $service_order_id       = Crypt::decryptString($service_order_id);
        $service_order          = ServiceOrder::find($service_order_id);
        $general_report_id      = Crypt::decryptString($general_report_id);
        $this->general_report   = GeneralReport::find( $general_report_id );
        $this->stored           = $this->general_report->stored;
        if( !$this->stored ) $this->set_operators( $service_order_id );
        $this->id               = $this->general_report->id;
        $client_id              = $this->general_report->client_id;
        $this->client_name      = Client::where('id',$client_id)->select('name')->first()->name;
        $this->serial          = $service_order->serial;
        $this->preventive      = $this->general_report->preventive;
        $this->corrective      = $this->general_report->corrective;
        $this->client_equipment_id  = $this->general_report->client_has_equipment_id;
        if( $this->general_report->start_time  )  self::get_start_time();
        if( $this->general_report->end_time  )  self::get_end_time();

    }

    protected function get_start_time()
    {
        $this->start_time = Carbon::parse( $this->general_report->start_time )->format( 'd-m-Y g:i A' );
    }

    protected function get_end_time()
    {
        $this->end_time = Carbon::parse( $this->general_report->end_time )->format('d-m-Y g:i A');
    }
    protected function set_operators( $service_order_id )
    {
        $this->operator = ServiceOrderUser::where('service_order_id',$service_order_id)->get()->count();
    }

    protected function set_equipment_data( )
    {
        $client_equipment = DataClientEquipment::get_equipment( $this->client_equipment_id )
            ->select('brands.name as brand_name',
                'locations.name as location_name',
                'headquarters.name as headquarter_name',
                'equipment_classes.name as class_name',
                'volts.volt_measurement',
                'volts.unit as volt_unit',
                'amperes.amperage_measurement',
                'amperes.unit as ampere_unit',
                'clients_has_equipments.serial as equipment_serial',
                'clients_has_equipments.internal_id',
                'clients_has_equipments.observations',
                'equipment_models.model as equipment_model',
                'equipments.name as equipment_name')->first();

        $this->equipment_name   = $client_equipment->equipment_name;
        $this->equipment_model  = $client_equipment->equipment_model;
        $this->equipment_serial = $client_equipment->equipment_serial;
        $this->location         = $client_equipment->location_name;
        $this->brand            = $client_equipment->brand_name;
        $this->volt_measurement = $client_equipment->volt_measurement;
        $this->volt_unit = $client_equipment->volt_unit;
        $this->amperage_measurement = $client_equipment->amperage_measurement;
        $this->ampere_unit = $client_equipment->ampere_unit;

    }

    protected function set_activities()
    {
        $preventive =  $this->general_report->preventive;
        $corrective =  $this->general_report->corrective;
        if( $preventive )  $this->set_activities_preventive();
        if(  $corrective ) $this->set_corrective_activities();
    }


    protected function set_activities_preventive()
    {
        $this->preventive_routine = $this->general_report->preventive_routine;
        $preventive_routine_id    = PreventiveRoutine::where('name', $this->preventive_routine )
            ->select('id')->first()->id;

        $preventive_activity_ids = PreventiveRoutineActivity::where('preventive_routine_id',$preventive_routine_id)
            ->select('preventive_activity_id')->get()->toArray();
        $this->preventive_activities = PreventiveActivity::whereIn('id',$preventive_activity_ids)->select('id','activity')
            ->get();

    }

    protected function set_corrective_activities()
    {
        $service_order_id = Crypt::decryptString($this->service_order_id);
        $this->corrective_activities = ClientsEquipmentsCorrective::join('corrective_services','clients_equipments_correctives.corrective_service_id','corrective_services.id')
            ->join('corrective_activities','clients_equipments_correctives.corrective_activity_id','corrective_activities.id')
            ->where('corrective_services.service_order_id',$service_order_id)
            ->distinct('clients_equipments_correctives.corrective_activity_id')
            ->select('corrective_activities.activity','corrective_activities.id')
            ->get()->toArray();
    }


    protected function set_input_spare()
    {
        foreach ($this->used_spare_parts as $spare_part ){
            $this->spare_part_input[$spare_part->id] = '';
        }
    }

    protected function set_materials_inputs()
    {
        foreach ($this->used_materials as $material )
        {
            $this->material_inputs[$material->id]='';
            $this->material_select[$material->id]='';
        }
    }

    protected function initializePhotoInputs(): void
    {
        $this->photoInputs = [];

        $this->general_report->loadMissing('photos');
        $existingPhotos = $this->general_report->photos ?? collect();

        if ($existingPhotos->isEmpty()) {
            $this->photoInputs[] = $this->blankPhotoInput();
            return;
        }

        foreach ($existingPhotos as $photo) {
            $this->photoInputs[] = [
                'id' => $photo->id,
                'file' => null,
                'flag' => true,
                'title_photo_id' => $photo->title_photo_id,
                'existing_path' => $photo->path,
            ];
        }
    }

    protected function blankPhotoInput(): array
    {
        return [
            'id' => null,
            'file' => null,
            'flag' => false,
            'title_photo_id' => null,
            'existing_path' => null,
        ];
    }

    public function addPhotoInput(): void
    {
        $this->photoInputs[] = $this->blankPhotoInput();
    }

    public function removePhotoInput(int $index): void
    {
        if (! array_key_exists($index, $this->photoInputs)) {
            return;
        }

        $photo = $this->photoInputs[$index];

        if (!empty($photo['id'])) {
            $this->removedPhotoIds[] = $photo['id'];
            $this->removedPhotoIds = array_values(array_unique($this->removedPhotoIds));
        }

        unset($this->photoInputs[$index]);
        $this->photoInputs = array_values($this->photoInputs);

        if (empty($this->photoInputs)) {
            $this->photoInputs[] = $this->blankPhotoInput();
        }
    }

    public function updatedPhotoInputs($value, $key): void
    {
        if (!is_string($key)) {
            return;
        }

        if (Str::endsWith($key, '.file') && $value instanceof TemporaryUploadedFile) {
            $segments = explode('.', $key);
            if (count($segments) >= 2) {
                $index = (int) $segments[0];
                if (array_key_exists($index, $this->photoInputs)) {
                    $this->photoInputs[$index]['flag'] = true;
                }
            }
        }

        if (Str::endsWith($key, '.title_photo_id')) {
            $segments = explode('.', $key);
            if (count($segments) >= 2) {
                $index = (int) $segments[0];
                if (array_key_exists($index, $this->photoInputs)) {
                    $this->photoInputs[$index]['title_photo_id'] = $value ?: null;
                }
            }
        }
    }

    public function store_preventive_activity()
    {
        $preventive_activity = $this->preventive_activities_check;
        foreach ( $preventive_activity as $key => $preventive_activity_id ){
            GeneralReportPreventive::create(
                [
                    'preventive_activity_id'=>$preventive_activity_id,
                    'general_report_id' => $this->general_report->id

                ]
            );
        }
    }

    protected function store_corrective_activities()
    {
        $corrective_activities = $this->corrective_activities_check;
        foreach ($corrective_activities as $key => $corrective_activity_id  ){
            GeneralReportCorrective::create([
                'corrective_activity_id'=>   $corrective_activity_id,
                'general_report_id' =>        $this->general_report->id
            ]);
        }
    }

    protected function store_spare_part()
    {
        $spare_parts = $this->spare_part_check;
        foreach ($spare_parts as $key => $spare_part_id   )
        {
            GeneralReportSparePart::create([
                'amount'            => $this->spare_part_input[$spare_part_id],
                'spare_part_id'     => $spare_part_id,
                'general_report_id' => $this->general_report->id,
            ]);
        }
    }

    protected function store_materials()
    {
        $materials = $this->materials_check;
        foreach ($materials as $key => $material_id   )
        {
            GeneralReportMaterial::create([
                'amount'=> $this->material_inputs[ $material_id ],
                'material_id' => $material_id,
                'general_report_id' => $this->general_report->id,
                'unit_id' => $this->material_select[ $material_id ],
            ]);
        }
    }

    protected function validate_spare_check()
    {
        $validator = true;
        foreach ($this->spare_part_input as $index => $spare  )
        {
            if( $spare && empty( $this->spare_part_check ) ){
                $validator = false;
                break;
            }
        }
        return  $validator;
    }


    protected function get_preventive_activities()
    {
        $preventive_activities =GeneralReportPreventive::where('general_report_id',$this->id)
            ->select('preventive_activity_id')->get()
            ->pluck('preventive_activity_id')->toArray();

        foreach ($preventive_activities as $index => $preventive_activity_id  ){
            $this->preventive_activities_check[]= $preventive_activity_id;
        }
    }


    protected function get_corrective_activities()
    {
        $corrective_activities = GeneralReportCorrective::where('general_report_id',$this->id)
            ->select('corrective_activity_id')->get()
            ->pluck('corrective_activity_id')->toArray();

        foreach (   $corrective_activities  as $index => $corrective_activity_id)
        {
            $this->corrective_activities_check[]= $corrective_activity_id;
        }
    }


    protected function get_spare_parts()
    {
        $spare_parts = GeneralReportSparePart::where('general_report_id',$this->id)
            ->select('spare_part_id','amount')->get();

        foreach ($spare_parts as $spare_part  ){
           $this->spare_part_check[] = $spare_part->spare_part_id;
           $this->spare_part_input[$spare_part->spare_part_id] = $spare_part->amount;
        }
    }




    protected function get_materials()
    {
        $materials = GeneralReportMaterial::where('general_report_id',$this->id)
            ->select('material_id','amount','unit_id')->get();

        foreach ($materials as  $material  ){
            $this->materials_check[] = $material->material_id;
            $this->material_inputs[$material->material_id] = $material->amount;
            $this->material_select[$material->material_id] = $material->unit_id;

        }
    }

    protected function validte_material_check()
    {
        $validator = true;
        foreach ($this->material_inputs as $index => $material ){
            if( $material && empty( $this->materials_check ) ){
                $validator = false;
                break;
            }
        }
        return $validator;
    }
    protected function validate_material_check_select()
    {

        $validator = true;
        foreach ($this->material_select as $index => $material ){
            if( $material && empty( $this->materials_check ) ){
                $validator = false;
                break;
            }
        }
        return $validator;
    }

    protected function validate_spare_parts_input()
    {
        $validator= true;
        foreach ( $this->spare_part_check as $index => $spare )
        {
            if( !$this->spare_part_input[$spare] || intval($this->spare_part_input[$spare]) < 0 ){
                $validator = false;
                break;
            }
        }

        return $validator;
    }

    protected function validate_material_input()
    {
        $validator = true;
        foreach ($this->materials_check  as $index => $material ){
            if( !$this->material_inputs[$material] || intval( $this->material_inputs[$material] ) < 0 ){
                $validator = false;
                break;
            }
        }

        return $validator;
    }


    protected  function validate_material_select()
    {
        $validator = true;
        foreach ($this->materials_check  as $index => $material ){
            if( !$this->material_select[$material]){
                $validator = false;
                break;
            }
        }

        return $validator;
    }

    protected function validatePhotos(): void
    {
        $rules = [];

        foreach ($this->photoInputs as $index => $photo) {
            $rules["photoInputs.$index.file"] = 'nullable|mimes:jpeg,png,jpg';
            $rules["photoInputs.$index.title_photo_id"] = 'nullable|exists:title_photos,id';
        }

        if ($rules) {
            $this->validate($rules, [
                'photoInputs.*.file.mimes' => 'El archivo debe ser formato jpg, png o jpeg.',
                'photoInputs.*.title_photo_id.exists' => 'El título seleccionado no es válido.',
            ]);
        }

        $errors = [];

        foreach ($this->photoInputs as $index => $photo) {
            $flag = $photo['flag'] ?? false;
            $file = $photo['file'] ?? null;
            $titleId = $photo['title_photo_id'] ?? null;
            $id = $photo['id'] ?? null;

            if ($flag) {
                if (!$id && !$file) {
                    $errors["photoInputs.$index.file"] = 'Espera un momento mientras la imagen es procesada.';
                }
                if (!$titleId) {
                    $errors["photoInputs.$index.title_photo_id"] = 'Selecciona un título para la imagen.';
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }


    protected function  storage_image()
    {
        $data =[
            'general_report'   => $this->general_report,
            'photos'           => $this->photoInputs,
            'removed'          => $this->removedPhotoIds,
        ];
        GeneralReportService::storage_image( $data );

        $this->removedPhotoIds = [];
    }



    #[On('save_signature')]
    public function  save_signature_image( $signatureDataUrl )
    {
      $this->signatureDataUrl = $signatureDataUrl;
      $this->signature_flag = true;
    }

    #[On('clear_signature')]
    public function clear_signature()
    {
        $this->signatureDataUrl = null;
        $this->signature_flag = false;
    }

    public function store_signature()
    {
        $data = [
            'general_report'    => $this->general_report,
            'id'                => $this->general_report->id,
            'signatureDataUrl'  => $this->signatureDataUrl
        ];
        /// Store signature
        GeneralReportService::update_or_stored_signature( $data );
    }

    public function startTime()
    {
          if( !$this->general_report->start_time ){
             $this->start_time = GeneralReportService::start_time( $this->general_report );
          }
    }

    public function rules()
    {
          return [
              'date'=>[
                  'required',
                  function (string $attribute, mixed $value, \Closure $fail) {
                      $date = Carbon::parse($value);
                      if (!$date->isToday() && $date->isAfter(Carbon::now())) {
                          $fail('La fecha seleccionada debe ser hoy o una fecha pasada. Por favor, selecciona una fecha válida.');
                      }
                  }
              ],
              'start_hour' => 'required',
              'end_hour' => 'required',
              'operator' => [
                  'required',
                  'numeric',
                  'gt:0',
              ],
              'description_service' => [
                  'nullable',
                  function( string $attribute, mixed $value, \Closure $fail ){
                      $value = trim( $value );
                      if( strlen( $value ) < 10 ){
                          $fail('La descipción debe contener la menos 10 caracteres');
                      }elseif ( strlen( $value ) > 610 ){
                          $fail('La descipción debe contener máximo 610 caracteres');
                      }
                  }
              ],
              'observations' => [
                  'nullable',
                  function( string $attribute, mixed $value, \Closure $fail ){
                      $value = trim( $value );
                      if( strlen( $value ) < 10 ){
                          $fail('La observación debe contener la menos 10 caracteres');
                      }elseif ( strlen( $value ) > 510 ){
                          $fail('La observación debe contener máximo 510 caracteres');
                      }
                  }
              ],
              'pending_note' => [
                  'nullable',
                  function( string $attribute, mixed $value, \Closure $fail ){
                      $value = trim( $value );
                      if( strlen( $value ) < 10 ){
                          $fail('La observación debe contener la menos 10 caracteres');
                      }elseif ( strlen( $value ) > 510 ){
                          $fail('La nota debe contener máximo 510 caracteres');
                      }
                  }
              ],
              'receptor_name'=>'required',
              'receptor_position'=>'required',
              'receptor_document_type'=>'required|in:cc',
              'receptor_document'=>'required|min:7',
          ];
    }


    public function messages()
    {
        return [
            'date.required' => 'La fecha es requerida.',
            'start_hour.required' => 'La hora inicial es requerida.',
            'end_hour.required' => 'La hora final es requerida.',
            'operator.required' => 'Este compo es requerido.',
            'operator.numeric' => 'El valor no es valido, ingrese un número.',
            'operator.gt' => 'El valor no es valido.',
            'photoInputs.*.file.mimes' => 'El archivo debe ser formato jpg, png o jpeg.',
            'photoInputs.*.title_photo_id.exists' => 'El título seleccionado no es válido.',
            'receptor_name.required' => 'El nombre es requerido.',
            'receptor_position.required' => 'El cargo es requerido.',
            'receptor_document_type.required' => 'El tipo de documento  es requerido.',
            'receptor_document.required' => 'El documento  es requerido.',
            'receptor_document.min' => 'El documento debe tener al menos 7 números.',
        ];
    }


}
