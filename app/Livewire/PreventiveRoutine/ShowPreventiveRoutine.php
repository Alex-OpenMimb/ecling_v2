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

     public $name, $frequency, $activities_list, $equipments_list, $equipment_class_name;

     #[Locked]
     public $id;

    public function mount(PreventiveRoutine $preventive_routine  )
    {
        $this->fill(
            $preventive_routine->only('id','name','frequency','equipment_class_id')
        );
        $this->activities_list = $this->get_activities();
        $this->equipments_list = $this->get_equipments();
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

    protected function get_equipments()
    {
        return PreventiveRoutineEquipment::select('brands.name as brand_name',
            'volts.volt_measurement',
            'volts.unit as volt_unit',
            'amperes.amperage_measurement',
            'amperes.unit as ampere_unit',
            'equipment_models.model as equipment_model',
            'equipments.name')
            ->join('equipments','preventive_routines_equipments.equipment_id','=','equipments.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->join('volts','equipments.volt_id','=','volts.id')
            ->leftJoin('amperes','equipments.ampere_id','=','amperes.id')
            ->where('preventive_routines_equipments.preventive_routine_id',$this->id)->get();
    }


}
