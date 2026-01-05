<?php

namespace App\Livewire\Material\Unit;

use App\Helper\HandleStatus;
use App\Models\Unit;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable   extends  Component
{
    use WithPagination;

    public $head, $counter = 1,$query;

    #[Locked]
    public $id;

    public function mount()
    {
        $this->head = ['Item','Unidad*','Acciones'];
    }


    #[On('reload_unit')]
    public function render()
    {
        $units = $this->get_units();
        return view( 'livewire.material.unit.datatable',['units'=>$units]);
    }


    protected function get_units()
    {
        $queries = trim($this->query);
        return Unit::select('id','unit_name','status')
            ->where('unit_name','like','%'. $queries .'%')
            ->orderBy('id','desc')
            ->simplePaginate(5);
    }


    public function search()
    {
        $this->resetPage();
    }

    public function status( Unit $unit )
    {
        HandleStatus::handle_status($unit, 'La unidad');
    }
}
