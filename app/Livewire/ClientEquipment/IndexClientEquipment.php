<?php

namespace App\Livewire\ClientEquipment;

use App\Models\Client;
use App\Models\Headquarter;
use App\Services\Headquarter\DataHeadquarter;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexClientEquipment  extends Component
{

    public $client_name,$headquarter_name;

    public $nomenclature_main, $number_main, $nomenclature_second, $number_second;
    public $number, $city_name;
    public $client, $headquarter;


    public $headquarter_id;



    public function mount( Client  $client, Headquarter $headquarter )
    {
        $this->client = $client;
        $this->headquarter = $headquarter;
        $this->headquarter_id = $headquarter->id;
        $this->client_name      = $client->name;
        $this->headquarter_name = $headquarter->name;
        $this->get_address();
    }


    public function render()
    {
        return view('livewire.clientEquipment.index');

    }



    public function get_address()
    {
        $address = DataHeadquarter::get_address_headquarter( $this->headquarter_id );

        $this->nomenclature_main   = $address->nomenclature_main;
        $this->number_main         = $address->number_main;
        $this->nomenclature_second = $address->nomenclature_second;
        $this->number_second       = $address->number_second;
        $this->number              = $address->number;
        $this->city_name           = $address->city_name;
    }
}
