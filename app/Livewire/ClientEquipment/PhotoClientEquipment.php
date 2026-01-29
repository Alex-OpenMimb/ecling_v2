<?php

namespace App\Livewire\ClientEquipment;

use App\Actions\Helpers\GetPhotos;
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

    public $client_name, $headquarter_name, $loader = '';

    /** @var array Paths de las fotos del equipo del cliente */
    public array $photo_paths = [];

    public function mount(Headquarter $headquarter, Client $client, ClientsEquipments $client_equipment)
    {
        $this->client_equipment  = $client_equipment;
        $this->client            = $client;
        $this->headquarter       = $headquarter;
        $this->client_name       = $client->name;
        $this->headquarter_name  = $headquarter->name;

        $this->photo_paths = GetPhotos::run([
            'model' => ClientsEquipments::class,
            'id'    => $client_equipment->id,
        ]);

    }

    public function download_photo_by_path(string $path)
    {
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            toastr()->success('Foto descargada exitosamente', 'Felicitaciones!');

            return $disk->download($path);
        }

        toastr()->error('La foto no existe', 'Opps!');

        return back();
    }

    public function render()
    {
        return view('livewire.clientEquipment.photo');
    }
}
