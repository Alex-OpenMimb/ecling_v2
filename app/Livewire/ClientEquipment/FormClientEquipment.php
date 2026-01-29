<?php

namespace App\Livewire\ClientEquipment;

use App\Actions\ClientEquipment\CreateClientEquipment;
use App\Actions\Equipment\CreateEquipment;
use App\Actions\PreventiveRoutineEquipment\CreatePreventiveRoutineEquipment;
use App\Helper\GeneralHelper;
use App\Helper\HandelSerial;
use App\Models\Client;
use App\Models\ClientsEquipments;
use App\Models\EquipmentClass;
use App\Models\Headquarter;
use App\Models\Location;
use App\Models\PreventiveRoutine;
use App\Models\TitlePhoto;
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
        public Client $client;
        public Headquarter $headquarter;

        #[Locked]
        public $id;

        // Propiedades para crear equipo
        public $name;
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

        public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
        {
            $this->headquarter = $headquarter;
            $this->client = $client;
            $this->client_equipment = $client_equipment;
            $this->get_equipment_class();
            $this->get_title_photos();
            $this->get_preventive_routines();
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

            // Crear el Equipment primero
            $equipmentData = [
                'name' => $this->name,
                'brand' => $this->brand,
                'location' => $this->location,
                'voltage' => $this->voltage,
                'amperage' => $this->amperage,
                'model' => $this->model,
                'equipment_class_id' => $this->equipment_class_id,
            ];

            $equipmentResult = CreateEquipment::run($equipmentData);
            $equipment = $equipmentResult['equipment'];

            // Crear el registro en clients_has_equipments
            $clientEquipmentData = [
                'observations' => $this->observations,
                'client_id' => $this->client->id,
                'headquarter_id' => $this->headquarter->id,
                'location' => $this->location,
                'equipment_class_id' => $this->equipment_class_id,
                'plate_photo' => $this->plate_photo,
                'perimeter_photo' => $this->perimeter_photo,
                'photo1_title_photo_id' => $this->photo1_title_photo_id,
                'photo2_title_photo_id' => $this->photo2_title_photo_id,
            ];

            $result = CreateClientEquipment::run($equipment, $clientEquipmentData);
            $clientEquipment = $result['client_equipment'];

            CreatePreventiveRoutineEquipment::run([
                'equipment_client' => $clientEquipment,
                'routine_id' => (int) $this->preventive_routine_id,
                'custom_frequency' => $this->custom_frequency !== '' && $this->custom_frequency !== null
                    ? (int) $this->custom_frequency
                    : null,
            ]);

            toastr()->success('El equipo se ha creado con éxito!', 'Felicitaciones');
            return redirect()->route('admin.clients-equipments', [
                'client' => $this->client->slug,
                'headquarter' => $this->headquarter->slug
            ]);
        }

        public function rules()
        {
            return [
                'name' => 'required|string|min:3',
                'brand' => 'required|string',
                'location' => 'required|string',
                'voltage' => 'required|numeric|min:0',
                'amperage' => 'required|numeric|min:0',
                'model' => 'required|string',
                'equipment_class_id' => 'required|exists:equipment_classes,id',
                'preventive_routine_id' => 'required|exists:preventive_routines,id',
                'custom_frequency' => 'nullable|numeric|min:0',
            ];
        }

        public function messages()
        {
            return [
                'name.required' => 'El nombre es requerido.',
                'name.string' => 'El nombre debe ser texto.',
                'name.min' => 'El nombre debe tener al menos 3 caracteres.',
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
