<?php

namespace App\Livewire\PreventiveRoutine;

use App\Helper\HandleStatus;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineActivity;
use App\Models\PreventiveRoutineEquipment;
use Livewire\Component;
use Livewire\WithPagination;

class DatatablePreventiveRoutine  extends Component
{

    use WithPagination;

    public $heads, $counter = 1,$query, $amount= 10;
    public function mount()
    {

        $this->heads = ['Item','Nombre*','Equipo','Frecuencia','Acciones'];
    }

    public function render()
    {
        $preventive_routines = $this->get_routines();
        return view('livewire.preventiveRoutine.datatable',['preventive_routines'=>$preventive_routines]);
    }



    public function get_routines()
    {
        $queries = trim($this->query);
        return PreventiveRoutine::select('preventive_routines.id',
            'preventive_routines.name',
            'preventive_routines.status',
            'preventive_routines.frequency',
             'equipment_classes.name as equipment_class')
            ->join('equipment_classes','preventive_routines.equipment_class_id','=','equipment_classes.id')
            ->where('preventive_routines.name','like','%'.$queries. '%')
            ->orWhere('equipment_classes.name','like','%'.$queries. '%')
            ->orderBy('preventive_routines.id','desc')
            ->simplePaginate( $this->amount);
    }



    public function status( PreventiveRoutine $preventive_routine )
    {
        HandleStatus::handle_status($preventive_routine, 'La rutina');
    }

    public function search()
    {
        $this->resetPage();
    }



}
