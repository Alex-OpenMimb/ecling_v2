<?php

namespace App\Livewire\ClientEquipment;

use App\Actions\ClientEquipment\CreateClientEquipment;
use App\Actions\Equipment\CreateEquipment;
use App\Actions\Helpers\StorePhoto;
use App\Actions\PreventiveRoutineEquipment\CreatePreventiveRoutineEquipment;
use App\Models\Ampere;
use App\Models\Brand;
use App\Models\Client;
use App\Models\ClientsEquipments;
use App\Models\Equipment;
use App\Models\EquipmentClass;
use App\Models\EquipmentModel;
use App\Models\Headquarter;
use App\Models\Location;
use App\Models\Photo;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineEquipment;
use App\Models\TitlePhoto;
use App\Models\Volt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;


class FormClientEquipment   extends  Component
{
        use WithFileUploads;

        public ClientsEquipments $client_equipment;
        public Client $client;
        public Headquarter $headquarter;

        #[Locked]
        public $id;

        // Propiedades para crear equipo
        public $name;
        public $quantity = 1;
        public $brand;
        public $location;
        public $voltage;
        public $amperage;
        public $model;
        public $equipment_class_id;
        public $observations;
        public $equipment_class_lists;
        public $title_photo_options;
        public $photo1_title_photo_id;
        public $photo2_title_photo_id;
        public $plate_photo;
        public $perimeter_photo;
        public $plate_photo_flag = false;
        public $perimeter_photo_flag = false;
        public $preventive_routine_id;
        public $custom_frequency;
        public $preventive_routine_lists = [];
        public $brand_options = [];
        public $model_options = [];
        public $voltage_options = [];
        public $amperage_options = [];
        public $location_options = [];
        public $equipments_list = [];
        public $selected_equipment_id = null;
        public $readonly = false;
        public $disabled = false;

        public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
        {

            if( $client_equipment->id  ){
                $this->setCreatedData( $client_equipment );
            }
            $this->headquarter = $headquarter;
            $this->client = $client;
            $this->client_equipment = $client_equipment;
            $this->get_equipment_class();
            $this->get_title_photos();
            $this->get_preventive_routines();
            $this->load_form_select_options();
            $this->load_equipments();
        }


        private function setStorageImage( $client_equipment )
        {
           foreach ( $client_equipment->photos as $photo ) {

           }
        }

        private function setCreatedData( $client_equipment )
        {
           $this->setStorageImage( $client_equipment );
            $this->readonly = true;
            $this->disabled = true;
            $this->id = $client_equipment->id;
            $this->equipment_class_id = $client_equipment->equipment->equipment_class_id;
            $this->name = $client_equipment->equipment->name;
            $this->brand = $client_equipment->equipment->brand->name;
            $this->model = $client_equipment->equipment->equipmentModel->model;
            $this->voltage = $client_equipment->equipment->volts->volt_measurement;
            $this->amperage = $client_equipment->equipment->amperes->amperage_measurement;
            $this->location = $client_equipment->location->name;
            if( $client_equipment->schedule_assigned  ){
                $this->setAssignedPreventiveRoutine();
            }


        }

        private function setAssignedPreventiveRoutine()
        {
            $preventive_routine =  PreventiveRoutineEquipment::join(
                'preventive_routines',
                'preventive_routines_equipments.preventive_routine_id',
                '=',
                'preventive_routines.id'
            )->where('preventive_routines_equipments.equipment_id',$this->id)
                ->select('preventive_routines_equipments.custom_frequency','preventive_routines.name','preventive_routines.id')
                ->first();
            $this->preventive_routine_id = $preventive_routine->id;
            $this->custom_frequency = $preventive_routine->custom_frequency;
        }

        protected function load_equipments(): void
        {
            $this->equipments_list = Equipment::select(
                'equipments.id',
                'equipments.name',
                'brands.name as brand_name',
                'equipment_models.model as model_name',
                'volts.volt_measurement as voltage',
                'amperes.amperage_measurement as amperage'
            )
                ->join('brands', 'equipments.brand_id', '=', 'brands.id')
                ->join('equipment_models', 'equipments.equipment_model_id', '=', 'equipment_models.id')
                ->join('volts', 'equipments.volt_id', '=', 'volts.id')
                ->leftJoin('amperes', 'equipments.ampere_id', '=', 'amperes.id')
                ->where('equipments.status', true)
                ->orderBy('equipments.name')
                ->get();
        }

        public function updated()
        {
            $this->load_equipments();
        }
        public function updatedSelectedEquipmentId($value): void
        {
            if (empty($value)) {
                return;
            }

            $equipment = Equipment::with(['brand', 'equipmentModel', 'volts', 'amperes'])
                ->where('id', $value)
                ->first();

            if ($equipment) {
                $this->name = $equipment->name;
                $this->brand = $equipment->brand ? $equipment->brand->name : '';
                $this->model = $equipment->equipmentModel ? $equipment->equipmentModel->model : '';
                $this->voltage = $equipment->volts ?  ( string ) $equipment->volts->volt_measurement : null;
                $this->amperage = $equipment->amperes ? ( string ) $equipment->amperes->amperage_measurement : null;
                $this->equipment_class_id = $equipment->equipment_class_id;
            }
            $this->load_equipments();
        }

        protected function load_form_select_options(): void
        {
            $this->brand_options = Brand::select('id', 'name')->orderBy('name')->get();
            $this->model_options = EquipmentModel::select('id', 'model')->orderBy('model')->get()->map(fn ($m) => ['name' => $m->model])->values()->all();
            $this->voltage_options = Volt::select('id', 'volt_measurement')->orderBy('volt_measurement')->get()->map(fn ($v) => ['name' => (string) $v->volt_measurement])->values()->all();
            $this->amperage_options = Ampere::select('id', 'amperage_measurement')->orderBy('amperage_measurement')->get()->map(fn ($a) => ['name' => (string) $a->amperage_measurement])->values()->all();
            $this->location_options = Location::select('id', 'name')->orderBy('name')->get();
        }

        protected function get_equipment_class()
        {
            $this->equipment_class_lists = EquipmentClass::select('id','name')
                ->where('status',true)
                ->get();
        }

        protected function get_title_photos()
        {
            $this->title_photo_options = TitlePhoto::select('id','title')
                ->where('status',true)
                ->get();
        }

        protected function get_preventive_routines()
        {
            $this->preventive_routine_lists = PreventiveRoutine::select('id','name')
                ->where('status',true)
                ->get();
        }

        public function render()
        {
            return view('livewire.clientEquipment.form');
        }


        public function updateOrStore( )
        {
            $this->validate();
            // Crear la cantidad de equipos para el cliente
            $quantity = (int) $this->quantity;
          if( !$this->id ){
              // Crear el Equipment primero
              $equipmentData = [
                  'name' => $this->name,
                  'brand' => $this->brand,
                  'location' => $this->location,
                  'voltage' => $this->voltage,
                  'amperage' => $this->amperage,
                  'model' => $this->model,
                  'equipment_class_id' => $this->equipment_class_id,
                  'asset_assignment' => true,
              ];

              $equipmentResult = CreateEquipment::run($equipmentData);
              $equipment = $equipmentResult['equipment'];


              for ($i = 0; $i < $quantity; $i++) {
                  // Crear el registro en clients_has_equipments
                  // Solo el primer equipo tendrá las fotos (los UploadedFile no se pueden reutilizar)
                  $clientEquipmentData = [
                      'observations' => $this->observations,
                      'client_id' => $this->client->id,
                      'headquarter_id' => $this->headquarter->id,
                      'location' => $this->location,
                      'equipment_class_id' => $this->equipment_class_id,
                      'plate_photo' =>  $this->plate_photo,
                      'perimeter_photo' => $this->perimeter_photo,
                      'photo1_title_photo_id' => $this->photo1_title_photo_id,
                      'photo2_title_photo_id' =>  $this->photo2_title_photo_id,
                  ];

                  $result = CreateClientEquipment::run($equipment, $clientEquipmentData);
                  $clientEquipment = $result['client_equipment'];

                  if ($this->preventive_routine_id) {
                      CreatePreventiveRoutineEquipment::run([
                          'equipment_client' => $clientEquipment,
                          'routine_id' => (int) $this->preventive_routine_id,
                          'custom_frequency' => $this->custom_frequency !== '' && $this->custom_frequency !== null
                              ? (int) $this->custom_frequency
                              : null,
                      ]);

                      $this->markPreventiveRoutineAssigned($equipment, $clientEquipment);
                  }
              }
          } else {
              $location = Location::firstOrCreate(
                  ['name' => $this->location],
                  ['status' => true]
              );
              $this->client_equipment->location_id = $location->id;
              $this->client_equipment->save();

              // Guardar fotos si se subieron en la edición (reemplazar si ya existe)
              if ($this->plate_photo instanceof UploadedFile) {
                  $this->replacePhotoByBasePath($this->client_equipment, 'image/client_equipment/plate_photo');
                  $titlePhotoId = !empty($this->photo1_title_photo_id) ? (int) $this->photo1_title_photo_id : null;
                  StorePhoto::run([
                      'file' => $this->plate_photo,
                      'title_photo_id' => $titlePhotoId,
                      'model' => $this->client_equipment,
                      'base_path' => 'image/client_equipment/plate_photo',
                  ]);
              }
              if ($this->perimeter_photo instanceof UploadedFile) {
                  $this->replacePhotoByBasePath($this->client_equipment, 'image/client_equipment/perimeter_photo');
                  $titlePhotoId = !empty($this->photo2_title_photo_id) ? (int) $this->photo2_title_photo_id : null;
                  StorePhoto::run([
                      'file' => $this->perimeter_photo,
                      'title_photo_id' => $titlePhotoId,
                      'model' => $this->client_equipment,
                      'base_path' => 'image/client_equipment/perimeter_photo',
                  ]);
              }
          }

            $message = $quantity === 1
                ? 'El equipo se ha creado/actualizado con éxito!'
                : "Se han creado {$quantity} equipos con éxito!";

            toastr()->success($message, 'Felicitaciones');
            return redirect()->route('admin.clients-equipments', [
                'client' => $this->client->slug,
                'headquarter' => $this->headquarter->slug
            ]);
        }

        protected function replacePhotoByBasePath(ClientsEquipments $clientEquipment, string $basePath): void
        {
            $existing = Photo::where('model_type', ClientsEquipments::class)
                ->where('model_id', $clientEquipment->id)
                ->where('path', 'like', $basePath . '/%')
                ->get();

            foreach ($existing as $photo) {
                if (Storage::disk('public')->exists($photo->path)) {
                    Storage::disk('public')->delete($photo->path);
                }
                $photo->delete();
            }
        }

        protected function markPreventiveRoutineAssigned(Equipment $equipment, ClientsEquipments $clientEquipment): void
        {
            $equipment->routine_assignment = true;
            $clientEquipment->preventive_services = true;
            $clientEquipment->preventive_services_first = true;
            $equipment->save();
            $clientEquipment->save();
        }

        public function rules()
        {
            return [
                'name' => ['required', 'string', 'min:3'],
                'quantity' => 'required|integer|min:1',
                'brand' => 'required|string',
                'location' => 'required|string',
                'voltage' => 'required|numeric|min:0',
                'amperage' => 'required|numeric|min:0',
                'model' => 'required|string',
                'equipment_class_id' => 'required|exists:equipment_classes,id',
                'preventive_routine_id' => 'nullable|exists:preventive_routines,id',
                'custom_frequency' => 'nullable|numeric|min:0',
            ];
        }

        public function messages()
        {
            return [
                'name.required' => 'El nombre es requerido.',
                'name.string' => 'El nombre debe ser texto.',
                'name.min' => 'El nombre debe tener al menos 3 caracteres.',
                'quantity.required' => 'La cantidad es requerida.',
                'quantity.integer' => 'La cantidad debe ser un número entero.',
                'quantity.min' => 'La cantidad debe ser mayor o igual a 1.',
                'brand.required' => 'La marca es requerida.',
                'brand.string' => 'La marca debe ser texto.',
                'location.required' => 'La ubicación es requerida.',
                'location.string' => 'La ubicación debe ser texto.',
                'voltage.required' => 'Los voltios son requeridos.',
                'voltage.numeric' => 'Los voltios deben ser un número.',
                'voltage.min' => 'Los voltios deben ser mayor o igual a 0.',
                'amperage.required' => 'Los amperios son requeridos.',
                'amperage.numeric' => 'Los amperios deben ser un número.',
                'amperage.min' => 'Los amperios deben ser mayor o igual a 0.',
                'model.required' => 'El modelo es requerido.',
                'model.string' => 'El modelo debe ser texto.',
                'equipment_class_id.required' => 'La clase de equipo es requerida.',
                'equipment_class_id.exists' => 'La clase de equipo seleccionada no es válida.',
                'preventive_routine_id.required' => 'Debe seleccionar una rutina preventiva.',
                'preventive_routine_id.exists' => 'La rutina seleccionada no es válida.',
                'custom_frequency.numeric' => 'La frecuencia personalizada debe ser un número.',
                'custom_frequency.min' => 'La frecuencia personalizada debe ser mayor o igual a 0.',
            ];
        }



}
