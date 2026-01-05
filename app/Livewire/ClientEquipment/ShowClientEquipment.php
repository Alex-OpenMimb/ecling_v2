<?php

namespace App\Livewire\ClientEquipment;

use App\Services\ClientEquipment\DataClientEquipment;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ShowClientEquipment  extends  ModalComponent
{

   public $equipment_model, $brand_name, $location, $headquarter, $client;
   public $equipment_class, $volt, $volt_unit, $ampere, $ampere_unit, $serial, $internal_id;
   public $observations,$equipment_name;

   #[Locked]
   public $client_equipment_id;

    public function mount( $client_equipment_id )
    {
          $this->client_equipment_id = $client_equipment_id;
          $this->set_data_equipment();
    }
    public function render()
    {
        return view('livewire.clientEquipment.show');
    }



    protected function set_data_equipment()
    {
        $client_equipment = DataClientEquipment::get_equipment( $this->client_equipment_id )
                            ->select('brands.name as brand_name',
                                 'locations.name as location_name',
                                 'headquarters.name as headquarter_name',
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
                                    'equipments.name as equipment_name')->first();

        $this->brand_name = $client_equipment->brand_name;
        $this->location = $client_equipment->location_name;
        $this->equipment_model = $client_equipment->equipment_model;
        $this->headquarter = $client_equipment->headquarter_name;
        $this->client = $client_equipment->client_name;
        $this->equipment_class = $client_equipment->class_name;
        $this->volt = $client_equipment->volt_measurement;
        $this->volt_unit = $client_equipment->volt_unit;
        $this->ampere = $client_equipment->amperage_measurement;
        $this->ampere_unit = $client_equipment->ampere_unit;
        $this->serial = $client_equipment->serial;
        $this->internal_id = $client_equipment->internal_id;
        $this->observations = $client_equipment->observations;
        $this->equipment_name = $client_equipment->equipment_name;


    }


}
