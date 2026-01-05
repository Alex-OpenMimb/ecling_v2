<?php

namespace App\Livewire\Store\Location;

use App\Helper\HandleStatus;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableLocation extends Component
{
    use WithPagination;

    public $name;
    public $head, $counter = 1, $query;

    #[Locked]
    public $id;
    public function mount()
    {
        $this->head = ['item','Nombre*','Estado','Acciones'];
    }

    #[On('reload_location')]
    public function render()
    {
        $queries = trim($this->query);
        $locations =  Location::select('locations.id','locations.name','locations.status')
            ->selectRaw('(SELECT COUNT(*) FROM clients_has_equipments WHERE clients_has_equipments.location_id = locations.id AND clients_has_equipments.deleted_at IS NULL) as client_equipments_count')
            ->where('locations.name','like','%'.$queries. '%')
            ->orderBy('locations.id','desc')->simplePaginate(5);
        return view('livewire.store.location.datatable',['locations'=>$locations]);
    }


    public function search()
    {
        $this->resetPage();
    }

    public function status( Location $location )
    {
        HandleStatus::handle_status($location, 'La ubicación');
    }
}
