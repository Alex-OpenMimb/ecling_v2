<?php

namespace App\Livewire\Material\Material;

use App\Helper\HandleStatus;
use App\Models\Material;
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


    #[On('reload_material')]
    public function render()
    {
        $materials = $this->get_materials();
        return view( 'livewire.material.materials.datatable',['materials'=>$materials]);
    }



    protected function get_materials()
    {
        $queries = trim($this->query);
        return Material::select('id','material_name','status')
            ->where('material_name','like','%'. $queries .'%')
            ->orderBy('id','desc')
            ->simplePaginate(5);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function status( Material $material )
    {
        HandleStatus::handle_status($material, 'El material');
    }
}
