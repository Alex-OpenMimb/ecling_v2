<?php

namespace App\Livewire\Material\SparePart;

use App\Helper\HandleStatus;
use App\Models\SparePart;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable  extends Component
{
    use WithPagination;

    public $head, $counter = 1,$query;

    #[Locked]
    public $id;

    public function mount()
    {
        $this->head = ['Item','Nombre*','Acciones'];
    }


    #[On('reload_spare_part')]
    public function render()
    {
        $parts = $this->get_spare_part();
        return view( 'livewire.material.sparePart.datatable',['parts'=>$parts]);
    }



    protected function get_spare_part()
    {
        $queries = trim($this->query);
        return SparePart::select('id','spare_part_name','status')
            ->where('spare_part_name','like','%'. $queries .'%')
            ->orderBy('id','desc')
            ->simplePaginate(5);
    }


    public function search()
    {
        $this->resetPage();
    }

    public function status( SparePart $sparePart )
    {
        HandleStatus::handle_status($sparePart, 'El repuesto');
    }
}
