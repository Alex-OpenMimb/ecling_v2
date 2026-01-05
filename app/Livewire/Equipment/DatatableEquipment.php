<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableEquipment extends Component
{
    use WithPagination;

    public $heads, $counter,$query, $amount = 10;

    public function mount( )
    {
        $this->counter = 1;
        $this->heads = ['Items','Nombre*','Modelo*','Marca*','Clase de equipo*','Voltios','Amperios','Acciones'];

    }

    public function render()
    {
        $equipments = $this->get_equipments()->simplePaginate( $this->amount );
        return view('livewire.equipment.datatable',['equipments'=>$equipments]);
    }


    protected function get_equipments()
    {
        $queries = trim($this->query);
        return Equipment::select('equipments.id',
            'equipments.name',
            'equipments.slug',
            'brands.name as brand_name'
            ,'equipment_classes.name as name_class',
            'volts.volt_measurement as volt',
            'amperes.amperage_measurement as ampere',
             'equipment_models.model',
               'equipments.asset_assignment')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('volts','equipments.volt_id','=','volts.id')
            ->leftJoin('amperes','equipments.ampere_id','=','amperes.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->where(function($query) use ($queries) {
                $query->orWhere('brands.name', 'like', '%'.$queries.'%')
                    ->orWhere('equipment_classes.name', 'like', '%'.$queries.'%')
                    ->orWhere('equipment_models.model', 'like', '%'.$queries.'%')
                    ->orWhere('equipments.name', 'like', '%'.$queries.'%');
            })->orderBy('equipments.id','desc');
    }


    public function search()
    {
        $this->resetPage();
    }
}
