<?php

namespace App\Livewire\EquipmentClass;

use App\Models\EquipmentClass;
use Livewire\Component;

class Show extends Component
{
    public EquipmentClass $equipmentClass;
    public $hasEquipments = false;

    public function mount(EquipmentClass $equipmentClass): void
    {
        $this->equipmentClass = $equipmentClass;
        $this->hasEquipments = $this->equipmentClass->equipments()->exists();
    }

    public function delete()
    {
        if ($this->hasEquipments) {
            toastr()->error('No se puede eliminar la clase de equipo porque está siendo utilizada por uno o más equipos.', 'Error');
            return;
        }

        $this->equipmentClass->delete();

        toastr()->success('La clase de equipo se ha eliminado con éxito!', 'Felicitaciones');

        return redirect()->route('admin.configurations.equipment-class.index');
    }

    public function render()
    {
        return view('livewire.equipmentClass.show');
    }
}

