<?php

namespace App\Livewire\CorrectiveActivity;

use App\Helper\HandleStatus;
use App\Models\CorrectiveActivity;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableCorrective extends Component
{

    use WithPagination;

    public $heads, $counter = 1,$query, $amount;


    public function mount()
    {
        $this->heads = ['Item','Actividad*','Descripción','Estado','Acciones'];
    }


    #[On('reload_corrective_activity')]
    public function render()
    {
        $activities = $this->get_corrective_activities();
        return view('livewire.correctiveActivity.datatable',['activities'=>$activities]);
    }



    protected function get_corrective_activities()
    {
        $queries = trim($this->query);
        return CorrectiveActivity::select('activity','description','status','id')
            ->where('activity','like','%'.$queries. '%')
            ->orderBy('id','desc')
            ->simplePaginate( $this->amount );
    }


    public function status(CorrectiveActivity $activity )
    {
        HandleStatus::handle_status($activity, 'La actividad');
    }

    public function search()
    {
        $this->resetPage();
    }
}
