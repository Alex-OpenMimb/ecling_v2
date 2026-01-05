<?php

namespace App\Livewire\PreventiveActivity;

use App\Helper\HandleStatus;
use App\Models\PreventiveActivity;
use App\Models\PreventiveRoutineActivity;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatablePreventive extends Component
{
    use WithPagination;

    public $heads, $counter, $mount,$query,$amount;

    public function mount( )
    {

        $this->counter = 1;
        $this->heads = ['Items','Nombre*','Clase de Equipo','Descripción', 'Estado', 'Acciones'];

    }


    #[On('reload_activity_preventive')]
    public function render()
    {
        $preventive_activities = $this->get_activities();
        return view('livewire.preventiveActivity.datatable',['preventive_activities'=>$preventive_activities]);

    }

    protected function get_activities()
    {
        $queries = trim($this->query);
        return PreventiveActivity::select('preventive_activities.id',
            'preventive_activities.activity',
            'preventive_activities.description',
            'preventive_activities.status',
            'equipment_classes.name')
            ->selectRaw('(SELECT COUNT(*) FROM general_report_preventive WHERE general_report_preventive.preventive_activity_id = preventive_activities.id AND general_report_preventive.deleted_at IS NULL) as general_reports_count')
            ->join('equipment_classes','preventive_activities.equipment_class_id','=','equipment_classes.id')
            ->where('preventive_activities.activity','like','%'.$queries. '%')
            ->orderBy('preventive_activities.id','desc')
            ->simplePaginate( $this->amount );


    }

    public function search()
    {
        $this->resetPage();
    }


    public function status( PreventiveActivity $preventiveActivity )
    {
        $preventive_activity_id = $preventiveActivity->id;
        $preventive_activity =  PreventiveRoutineActivity::where('preventive_activity_id',$preventive_activity_id)
            ->count();
        if( !$preventive_activity )HandleStatus::handle_status($preventiveActivity, 'La actividad');
        else{
            toastr()->error('La actividad está siendo utilizada, no se puede desactivar', 'Felicitaciones');
            $this->dispatch('reload');
        }

    }
}
