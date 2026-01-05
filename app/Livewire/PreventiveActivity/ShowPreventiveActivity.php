<?php

namespace App\Livewire\PreventiveActivity;

use App\Models\EquipmentClass;
use App\Models\PreventiveActivity;
use LivewireUI\Modal\ModalComponent;

class ShowPreventiveActivity extends ModalComponent
{

    public $activity, $equipment_class_id, $description, $equipment_class;


    public function  mount( PreventiveActivity $preventive_activity )
    {
        $this->fill(
            $preventive_activity->only('activity','description','equipment_class_id')
        );

        $this->equipment_class = EquipmentClass::getEquipmentClassesById( $this->equipment_class_id )
            ->first()->name;

    }


    public function render()
    {
        return view('livewire.preventiveActivity.show');

    }
}
