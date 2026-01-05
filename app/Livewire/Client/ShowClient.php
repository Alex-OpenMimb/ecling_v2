<?php

namespace App\Livewire\Client;

use App\Models\Client;
use App\Models\Headquarter;
use App\Services\Client\DataClient;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ShowClient extends ModalComponent
{
    public  $name,$nit,$email, $contact_name, $phone_1, $phone_2, $address, $headquarters_amount, $headquarter_name;
    public  $nomenclature_main, $number_main, $nomenclature_second,$number_second,$number,$city_name;
    public $observations;

    #[Locked]
    public  $id;

    public function mount( $client_id )
    {
        $client = Client::where('id',$client_id)->select('name','nit')->first();
        $headquarter = Headquarter::where('main',true)
            ->where('client_id',$client_id)->select('email')->first();
        $this->name  = $client->name;
        $this->nit   = $client->nit;
        $this->email = $headquarter->email;
        $this->id    = $client_id;
        $this->headquarters_amount = $this->get_headquarters_amount();
        $this->get_headquarter_data();
    }


    public function render()
    {
        return view('livewire.client.show');
    }

    protected function get_headquarter_data()
    {
        $headquarter = DataClient::main_address( $this->id );

        $this->headquarter_name    = $headquarter->name;
        $this->contact_name        = $headquarter->contact_name;
        $this->phone_1             = $headquarter->phone_1;
        $this->nomenclature_main   = $headquarter->nomenclature_main;
        $this->number_main         = $headquarter->number_main;
        $this->nomenclature_second = $headquarter->nomenclature_second;
        $this->number_second       = $headquarter->number_second;
        $this->number              = $headquarter->number;
        $this->city_name           = $headquarter->city_name;
        $this->observations        = $headquarter->observations;
    }


    protected function get_headquarters_amount()
    {
        return Headquarter::select()->where('client_id',$this->id)->count();
    }
}
