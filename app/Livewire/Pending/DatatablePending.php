<?php

namespace App\Livewire\Pending;

use App\Models\PendingActivity;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatablePending   extends Component
{


    use WithPagination;

    public $amount = 10, $heads, $counter = 1,$query;

    public function mount()
    {
        $this->heads= ['Items','Servicio*','Reporte','Fecha','Pendiente','Gestión','Estado*','Acciones'];
    }

    #[On('reload_pending_activity')]
    public function render()
    {
        $pending_activities = $this->get_pending();
        return view('livewire.pending.datatable',['pending_activities'=>$pending_activities]);
    }


    protected function  get_pending()
    {
        $queries = trim($this->query);

        return PendingActivity::join('general_reports','pending_activities.general_report_id','general_reports.id')
                              ->join('service_orders','pending_activities.service_order_id_flag','service_orders.id')
                              ->where('pending_activities.status','like','%'.$queries .'%')
                              ->orWhere('general_reports.serial','like','%'.$queries .'%')
                              ->select('service_orders.serial',
                                  'general_reports.date',
                                  'general_reports.serial as serial_report',
                                  'pending_activities.pending_note',
                                   'pending_activities.management_observations',
                                   'pending_activities.status',
                                   'pending_activities.id',
                                    )
                              ->simplePaginate( $this->amount );
    }


    public function search()
    {
        $this->resetPage();
    }
}
