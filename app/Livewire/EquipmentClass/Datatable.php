<?php

namespace App\Livewire\EquipmentClass;

use App\Helper\HandleStatus;
use App\Models\EquipmentClass;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $heads, $counter, $page;
    public $test_search = false;
    public $query = '', $amount = 10;

    public function mount()
    {
        $this->counter = 1;
        $this->heads = ['Items', 'Nombre', 'Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $equipmentClasses = $this->get_equipment_classes();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.equipmentClass.datatable', [
            'equipmentClasses' => $equipmentClasses,
        ]);
    }

    protected function get_equipment_classes()
    {
        $queries = trim($this->query);

        return EquipmentClass::select('equipment_classes.id', 'equipment_classes.name', 'equipment_classes.status', 'equipment_classes.created_at', 'equipment_classes.slug')
            ->selectRaw('(SELECT COUNT(*) FROM equipments WHERE equipments.equipment_class_id = equipment_classes.id AND equipments.deleted_at IS NULL) as equipments_count')
            ->when($queries, function ($query) use ($queries) {
                $query->where('equipment_classes.name', 'like', '%'.$queries.'%');
            })
            ->orderByDesc('equipment_classes.id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status(EquipmentClass $equipmentClass)
    {
        $this->test_search = false;
        
        if ($equipmentClass->equipments()->exists()) {
            toastr()->error('No se puede cambiar el estado de la clase de equipo porque está siendo utilizada por uno o más equipos.', 'Error');
            return;
        }
        
        HandleStatus::handle_status($equipmentClass, 'La clase de equipo');
    }

    #[On('delete_equipment_class')]
    public function delete($equipment_class_id)
    {
        $this->test_search = false;
        
        $equipmentClass = EquipmentClass::find($equipment_class_id);
        
        if (!$equipmentClass) {
            toastr()->error('La clase de equipo no fue encontrada.', 'Error');
            return;
        }
        
        if ($equipmentClass->equipments()->exists()) {
            toastr()->error('No se puede eliminar la clase de equipo porque está siendo utilizada por uno o más equipos.', 'Error');
            return;
        }

        $equipmentClass->delete();
        toastr()->success('La clase de equipo se ha eliminado con éxito!', 'Felicitaciones');
    }
}

