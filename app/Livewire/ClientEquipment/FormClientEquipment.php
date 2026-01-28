<?php

namespace App\Livewire\ClientEquipment;

use App\Actions\ClientEquipment\CreateClientEquipment;
use App\Actions\Equipment\CreateEquipment;
use App\Helper\GeneralHelper;
use App\Helper\HandelSerial;
use App\Models\Client;
use App\Models\ClientsEquipments;
use App\Models\EquipmentClass;
use App\Models\Headquarter;
use App\Models\Location;
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

        public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
        {
            $this->headquarter = $headquarter;
            $this->client = $client;
            $this->client_equipment = $client_equipment;
            $this->get_equipment_class();
            $this->get_title_photos();
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

            CreateClientEquipment::run($equipment, $clientEquipmentData);

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
            ];
        }






}
