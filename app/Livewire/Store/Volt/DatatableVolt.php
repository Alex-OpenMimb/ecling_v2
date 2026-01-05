<?php

namespace App\Livewire\Store\Volt;

use App\Helper\HandleStatus;
use App\Models\EquipmentModel;
use App\Models\Volt;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableVolt extends Component
{
    use WithPagination;

    public $head, $counter = 1,$query;

    #[Locked]
    public $id;

    public function mount()
    {
        $this->head = ['Item','Medida*','Unidad','Acciones'];
    }


    #[On('reload_volts')]
    public function render()
    {
        $queries = trim($this->query);
        $volts = Volt::select('id','volt_measurement','unit','status')
            ->where('volt_measurement','like','%'. $queries .'%')
            ->orderBy('id','desc')
            ->paginate(5);
        return view( 'livewire.store.volt.datatable',['volts'=>$volts]);
    }


    public function search()
    {
        $this->resetPage();
    }


    public function status( Volt $volt )
    {
        HandleStatus::handle_status($volt, 'El voltaje');
    }
}
