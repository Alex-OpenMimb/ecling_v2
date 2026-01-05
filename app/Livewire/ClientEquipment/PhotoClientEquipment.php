<?php

namespace App\Livewire\ClientEquipment;

use App\Helper\GeneralHelper;
use App\Models\Client;
use App\Models\ClientsEquipments;
use App\Models\Headquarter;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class PhotoClientEquipment extends Component
{
    public Client $client;
    public Headquarter $headquarter;
    public ClientsEquipments $client_equipment;

     public $client_name, $headquarter_name, $plate_photo,$perimeter_photo, $loader = '';

    public function mount(Headquarter  $headquarter, Client $client, ClientsEquipments $client_equipment )
    {
        $this->client_equipment  = $client_equipment;
        $this->client            = $client;
        $this->headquarter       = $headquarter;
         $this->client_name      = $client->name;
        $this->headquarter_name  = $headquarter->name;
        $this->perimeter_photo   = $client_equipment->perimeter_photo;
        $this->plate_photo       = $client_equipment->plate_photo;
        $this->plate_photo       =   $this->plate_photo ?  GeneralHelper::getImageUrl( $this->plate_photo ): null;
        $this->perimeter_photo   =   $this->perimeter_photo ? GeneralHelper::getImageUrl( $this->perimeter_photo ) : null;
    }

    public function download_photo( $type )
    {
         $disk = Storage::disk('space');
         $file_path = null;
         if( $type === 'perimeter' ){
             $file_path = $this->client_equipment->perimeter_photo;
         }elseif ( $type === 'plate' ){
             $file_path = $this->client_equipment->plate_photo;
         }

        if ($disk->exists($file_path)) {
            toastr()->success('Foto descargada exitosamente', 'Felicitaciones!');

            return $disk->download($file_path);
        } else {
            toastr()->error('La foto no existe', 'Opps!');
            return back();
        }

    }

    public function render()
    {
        return view('livewire.clientEquipment.photo');
    }
}
