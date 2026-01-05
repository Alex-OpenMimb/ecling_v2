<?php

namespace App\Livewire\Headquarter;

use App\Models\ClientEquipment;
use App\Models\Headquarter;
use App\Services\Headquarter\DataHeadquarter;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ShowHeadquarter  extends ModalComponent
{
    public  $name, $contact_name, $phone_1, $phone_2, $address,$equipments_amount,$observations;
    public  $nomenclature_main, $number_main, $nomenclature_second,$number_second,$number,$city_name;

    #[Locked]
    public  $id;

    public function mount( $headquarter_id)
    {
        $headquarter= Headquarter::where('id',$headquarter_id)
            ->select('name','contact_name','phone_1','phone_2')->first();
        $this->id           = $headquarter_id;
        $this->name         = $headquarter->name;
        $this->contact_name = $headquarter->contact_name;
        $this->phone_1      = $headquarter->phone_1;
        $this->phone_2      = $headquarter->phone_2;
       // $this->equipments_amount = $this->get_equipment_amount();
        $this->get_address_data();
    }


    public function render()
    {
        return view('livewire.headquarter.show');
    }


    protected function get_address_data()
    {
        $headquarter = DataHeadquarter::get_address_headquarter( $this->id );

        $this->nomenclature_main   = $headquarter->nomenclature_main;
        $this->number_main         = $headquarter->number_main;
        $this->nomenclature_second = $headquarter->nomenclature_second;
        $this->number_second       = $headquarter->number_second;
        $this->number              = $headquarter->number;
        $this->city_name           = $headquarter->city_name;
        $this->observations        = $headquarter->observations;

    }

    protected function get_equipment_amount()
    {
        //return ClientEquipment::select()->where('headquarter_id',$this->id)->count();
    }
}
