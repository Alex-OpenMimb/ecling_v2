<?php

namespace App\Livewire\Store\Model;

use App\Helper\HandleStatus;
use App\Models\EquipmentModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;


class DatatableModel  extends Component
{
    use WithPagination;

    public $name;
    public $head, $counter = 1,$query;

    #[Locked]
    public $id;
    public function mount()
    {
        $this->head = ['Item','Modelo*','Equipo','Estado','Acciones'];
    }



    #[On('reload_models')]
    public function render()
    {
        $queries = trim($this->query);
        $models = EquipmentModel::join('equipment_classes','equipment_models.equipment_class_id','equipment_classes.id')
              ->select('equipment_models.id',
                  'equipment_models.model',
                  'equipment_models.status',
                   'equipment_classes.name as class_name')
              ->selectRaw('(SELECT COUNT(*) FROM equipments WHERE equipments.equipment_model_id = equipment_models.id AND equipments.deleted_at IS NULL) as equipments_count')
            ->where('equipment_models.model','like','%'. $queries .'%')
            ->orderBy('equipment_models.id','desc')
            ->paginate(5);
        return view( 'livewire.store.model.datatable',['models'=>$models]);
    }

    public function status( EquipmentModel $model )
    {
        HandleStatus::handle_status($model, 'El modelo');
    }


    public function search()
    {
        $this->resetPage();
    }
}
