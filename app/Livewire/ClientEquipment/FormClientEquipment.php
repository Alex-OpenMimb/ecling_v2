<?php

namespace App\Livewire\ClientEquipment;

use App\Actions\ClientEquipment\CreateClientEquipment;
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

        public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
        {
            $this->headquarter = $headquarter;
            $this->client = $client;
            $this->client_equipment = $client_equipment;
        }
        public function render()
        {
            return view('livewire.clientEquipment.form');
        }


        public function updateOrStore( )
        {
            $this->validate();

            $data = [
                'name' => $this->name,
                'brand' => $this->brand,
                'location' => $this->location,
                'voltage' => $this->voltage,
                'amperage' => $this->amperage,
                'model' => $this->model,
                'equipment_class_id' => $this->equipment_class_id,
            ];

            $result = CreateClientEquipment::run($data);

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
