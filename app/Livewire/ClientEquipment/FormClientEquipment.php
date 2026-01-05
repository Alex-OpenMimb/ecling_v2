<?php

namespace App\Livewire\ClientEquipment;

use App\Helper\GeneralHelper;
use App\Helper\HandelSerial;
use App\Models\Client;
use App\Models\ClientsEquipments;
use App\Models\EquipmentClass;
use App\Models\Headquarter;
use App\Models\Location;
use App\Services\Equipment\DataEquipment;
use App\Services\Equipment\EquipmentService;
use App\Services\PreventiveRoutine\ServicePreventiveRoutine;
use App\Services\Schedule\ServicesSchedule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;


class FormClientEquipment   extends  Component
{
        use WithFileUploads;

        public ClientsEquipments $client_equipment;
        public $equipment_classes_list = [], $equipments_list= [], $locations_list =[], $action;

        public $equipment_class_id, $location_id, $equipment_id,$serial, $client, $headquarter,$plate_photo,$perimeter_photo;
        public $client_id, $headquarter_id, $client_name, $headquarter_name, $internal_id, $observations, $preventive_services;

        // It set to check if the image has send to backend correctly
        public $perimeter_flag = false, $plate_flag = false,$schedule_assigned,$preventive_services_first, $routine_validator;

        #[Locked]
        public $id;

        public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
        {
            if( $client_equipment->id ){
                $this->fill(
                    $client_equipment->only('id','plate','serial','observations'
                       ,'location_id','equipment_id','internal_id'
                        ,'plate_photo',
                        'perimeter_photo','schedule_assigned',
                        'preventive_services'
                        ,'preventive_services_first')
                );

               $this->get_equipment();
               $this->action = 1;
               $this->routine_validator = (bool)$this->preventive_services;
            }
            $this->set_data( $client, $headquarter );

        }
        public function render()
        {
            return view('livewire.clientEquipment.form');
        }

        protected function set_data( $client, $headquarter )
        {
            $this->client           = $client;
            $this->headquarter      = $headquarter;
            $this->client_id        = $client->id;
            $this->headquarter_id   = $headquarter->id;
            $this->client_name      = $client->name;
            $this->headquarter_name = $headquarter->name;
            $this->equipment_classes_list = EquipmentClass::getEquipmentClasses()->get();
            $this->locations_list = Location::getLocations()->get();
        }

        protected function get_equipment()
        {
           $equipment =  ClientsEquipments::join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
               ->where('clients_has_equipments.id', $this->id)
               ->select('equipments.equipment_class_id','clients_has_equipments.equipment_id')
               ->first();

           $this->equipment_class_id = $equipment->equipment_class_id;
           $this->equipments_list    = DataEquipment::get_equipments( $this->equipment_class_id );
        }
        public function updateOrStore( )
        {
            //Start validation
            if( $this->routine_validator ){
                $routines  =  ServicePreventiveRoutine::validator_preventive_routine( $this->equipment_id );
                if(empty($routines)) return  toastr()->error('El equipo seleccionado no tiene una rutina asignada!', 'Error');

            }

            $this->validate();
            $error_message = 'La foto aún no ha sido cargada';
            if( !$this->id ){

                $this->validate([
                    'plate_photo' => 'nullable|image|mimes:jpeg,png,jpg|dimensions:max_width=2000,max_height=2000',
                    'perimeter_photo' => 'nullable|image|mimes:jpeg,png,jpg|dimensions:max_width=2000,max_height=2000',
                ]);

                if( $this->plate_flag && !$this->plate_photo ) {
                    return  toastr()->error($error_message, 'Opps!');
                }

                if( $this->perimeter_flag && !$this->perimeter_photo )  {
                    return  toastr()->error($error_message, 'Opps!');
                }

            }else{

                 if( $this->plate_flag ){
                     $this->validate([
                         'plate_photo' => 'nullable|image|mimes:jpeg,png,jpg',
                     ]);
                 }
                 if( $this->perimeter_flag ){
                     $this->validate([
                         'perimeter_photo' => 'nullable|image|mimes:jpeg,png,jpg',
                     ]);
                 }

            }
            //End validation
            $client_equipment_id = ['id'=>$this->id];
            //If id incoming is not necessary  create the serial,otherwise  create them.
                if(!$this->id){
                    //create
                    $this->internal_id = HandelSerial::build_equipment_serial('clients_has_equipments',$this->equipment_class_id);
                }
                $data                   = $this->build_data();
                $this->client_equipment = ClientsEquipments::updateOrCreate($client_equipment_id,$data);

            //Storage the images
            $this->storage_image( $this->client_equipment );

            $message = 'actualizado';
            if(!$this->id ) {
                //Create
                EquipmentService::asset_assign( $this->equipment_id );
                $message = 'creado';
                //Invoke method the schedule for this equipment's clients
                 if( $this->routine_validator ){
                     ServicesSchedule::create_schedule( $this->client_equipment );
                     $this->client_equipment->preventive_services = 1;
                     $this->client_equipment->save();
                 }

            }else{
                //Edit

                 //If the routine has been assigned doesnt create schedule
                if( $this->routine_validator && !$this->preventive_services_first ) ServicesSchedule::create_schedule( $this->client_equipment );
                //
                if(  $this->routine_validator &&
                    $this->preventive_services_first &&
                    !$this->client_equipment->preventive_services){
                        ServicesSchedule::update_inactive_schedule( $this->client_equipment );
                }

                $this->client_equipment->preventive_services = (bool)$this->routine_validator;
                $this->client_equipment->save();

            }
            toastr()->success('Los datos se han '. $message .' con éxito!', 'Felicitaciones');
            $this->dispatch('reload_client_equipment');
            redirect()->route('admin.clients-equipments',['client'=> $this->client->slug,'headquarter' =>$this->headquarter->slug ]);
        }



        public function updatedEquipmentClassId()
        {
              $this->equipments_list = DataEquipment::get_equipments( $this->equipment_class_id );
        }


        protected function handel_photo( $photo_file, $photo_type, $id )
        {
            $extension = $photo_file->extension();
            if (app()->environment('local')) {
                $path = 'local/image/client_equipment/';
            }else{
                $path = 'image/client_equipment/';
            }
            return $photo_file->storeAs($path. $id , $photo_type .'.'.$extension, 'space');
        }


        protected function storage_image( $client_equipment )
        {
            $id = $client_equipment->id;
            if( $this->plate_photo && $this->plate_flag) {
                $this->plate_photo =  $this->handel_photo( $this->plate_photo, 'plate',$id );
                $client_equipment->update([
                    'plate_photo'=>$this->plate_photo
                ]);


            }

            if( $this->perimeter_photo && $this->perimeter_flag) {
                $this->perimeter_photo =  $this->handel_photo( $this->perimeter_photo, 'perimeter', $id );
                $client_equipment->update([
                    'perimeter_photo'=>$this->perimeter_photo
                ]);



            }
        }


    protected function build_data()
    {
        return [
            'internal_id'=>$this->internal_id,
            'serial'     =>$this->serial,
            'slug'       => Str::slug($this->internal_id,'-'),
            'location_id'=> $this->location_id,
            'observations'=> $this->observations,
            'status' => true,
            'equipment_id'=> $this->equipment_id,
            'client_id'=> $this->client_id,
            'headquarter_id'=> $this->headquarter_id,
        ];
    }



    public function rules()
    {
        return [
            'serial'=> [
                'nullable',
                Rule::unique('clients_has_equipments')->where( function ($query) {
                    return $query->where('serial',$this->serial);
                })->ignore($this->id,'id')
            ],
            'equipment_id'=> 'required|exists:equipments,id',
            'location_id'=> 'required|exists:locations,id',
            'observations' => [
                'nullable',
                'string',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 10 ){
                        $fail('La description debe contener la menos 10 caracteres');
                    }
                }
            ],

        ];
    }

    public function messages()
    {
        return [
            'equipment_id.required' => 'El equipos es requerido.',
            'serial.unique' => 'La placa ya está en uso.',
            'location_id.required' => 'La ubicación es requerida.',
            'location_id.exists' => 'La ubicación es requerida.',
            'plate_photo.image' => 'El archivo debe ser formato jpg, png o jpeg.',
            'plate_photo.dimensions' => 'La foto tiene dimensiones de imagen inválidas.',
            'perimeter_photo.image' => 'El archivo debe ser formato jpg, png o jpeg.',
            'perimeter_photo.dimensions' => 'La foto tiene dimensiones de imagen inválidas.',
        ];

    }




}
