<?php

namespace App\Livewire\Headquarter;

use App\Models\Client;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class IndexHeadquarter  extends Component
{
    #[Locked]
    public $client_slug;

    public $client_name,$status;

    public function mount(Client $client )
    {
        $this->client_slug = $client->slug;
        $this->client_name = $client->name;
        $this->status = $client->status;

    }

    public function render()
    {
        return view('livewire.headquarter.index');

    }

    #[On('inactive_client')]
    public function show_error_msm()
    {
        return  toastr()->error('No es posible crear una sucursal porque el cliente está desactivado.', 'Error');
    }
}
