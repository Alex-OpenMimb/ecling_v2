<?php

namespace App\Livewire\Store\Ampere;

use App\Helper\HandleStatus;
use App\Models\Ampere;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableAmpere extends Component
{
    use WithPagination;

    public $head, $counter = 1,$query;

    #[Locked]
    public $id;

    public function mount()
    {
        $this->head = ['Item','Medida*','Unidad','Acciones'];
    }


    #[On('reload_ampere')]
    public function render()
    {
        $queries = trim($this->query);
        $amperes = Ampere::select('id','amperage_measurement','unit','status')
            ->where('amperage_measurement','like','%'. $queries .'%')
            ->orderBy('id','desc')
            ->paginate(5);
        return view( 'livewire.store.ampere.datatable',['amperes'=>$amperes]);
    }



    public function search()
    {
        $this->resetPage();
    }


    public function status( Ampere $ampere )
    {
        HandleStatus::handle_status($ampere, 'El amperaje');
    }

}
