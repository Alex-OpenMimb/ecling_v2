<?php

namespace App\Livewire\PreventiveRoutine;

use App\Models\EquipmentClass;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineActivity;
use App\Models\PreventiveRoutineEquipment;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ShowPreventiveRoutine  extends ModalComponent
{

     public $name, $frequency, $activities_list, $equipment_class_name;

     #[Locked]
     public $id;

    public function mount(PreventiveRoutine $preventive_routine  )
    {
        $this->fill(
            $preventive_routine->only('id','name','frequency','equipment_class_id')
        );
        $this->activities_list = $this->get_activities();
        $this->equipment_class_name = EquipmentClass::where('id',$preventive_routine->equipment_class_id)
                                                      ->first()->name;
    }


    public function render()
    {
        return view('livewire.preventiveRoutine.show');
    }




    protected function get_activities()
    {
        return PreventiveRoutineActivity::select('preventive_activities.activity')
            ->join('preventive_activities','preventive_routines_activities.preventive_activity_id','=','preventive_activities.id')
            ->where('preventive_routines_activities.preventive_routine_id',$this->id)->get();
    }




}
