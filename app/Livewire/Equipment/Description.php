<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use LivewireUI\Modal\ModalComponent;

class Description  extends  ModalComponent
{

   public $description;

    public function mount(  $equipment_id )
    {
        $this->description =  Equipment::where('id',$equipment_id)
            ->select('description')
            ->first()->description;

    }


    public function render()
    {
        return view('livewire.equipment.description');
    }
}
