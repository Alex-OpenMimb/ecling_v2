<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use App\Services\Schedule\ScheduleData;
use App\Services\User\HandelCache;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableSchedule extends Component
{

    use WithPagination;

    public Schedule $schedule;
    public $counter = 1, $schedule_list = [];

    public $query = '',$amount = 10;

    public function mount()
    {
        $this->schedule_list= [];
        HandelCache::deleted_cache('schedule');

    }
    #[On('update_frequency')]
    public function render()
    {
        $schedules = $this->get_schedule()
            ->simplePaginate($this->amount);
        return view('livewire.schedule.datatable',['schedules'=>$schedules]);
    }


    protected function get_schedule()
    {
        $queries = trim($this->query);

        return Schedule::select('schedules.id',
            'schedules.preventive_routine_id' ,
            'equipments.name as equipment_name' ,
            'clients.name as client_name',
            'clients_has_equipments.serial',
            'schedules.active',
            'schedules.last_date',
            'schedules.next_date',
            'schedules.frequency',
            'schedules.days',
            'schedules.status',
            'headquarters.id as headquarter_id',
            'clients_has_equipments.id as equipment_id',
            'equipment_classes.id as equipment_class_id'
            ,'preventive_routines.name as preventive_routine_name'
            ,'headquarters.name as headquarter_name')
            ->join('preventive_routines','schedules.preventive_routine_id','preventive_routines.id')
            ->join('clients_has_equipments','schedules.client_has_equipment_id','=','clients_has_equipments.id')
            ->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('clients','clients_has_equipments.client_id','=','clients.id')
            ->join('headquarters','clients_has_equipments.headquarter_id','headquarters.id')
            ->where(function ($query){
                $query->where('clients.status',true)
                    ->where('clients_has_equipments.status',true)
                    ->where('clients_has_equipments.preventive_services',true);
            })
            ->where(function ($query) use ($queries){
                $query->orWhere('clients_has_equipments.serial','like','%'.$queries.'%')
                    ->orWhere('clients.name','like','%'.$queries.'%')
                    ->orWhere('equipments.name','like','%'.$queries.'%')
                    ->orWhere('equipment_classes.name','like','%'.$queries.'%')
                    ->orWhere('schedules.last_date','like','%'.$queries.'%')
                    ->orWhere('schedules.next_date','like','%'.$queries.'%')
                    ->orWhere('schedules.status','like','%'.$queries.'%')
                    ->orWhere('preventive_routines.name','like','%'.$queries.'%');
            })->orderBy('schedules.days','asc');


    }


    #[On('validate_schedule')]
    public function schedule_redirect_event()
    {
        $query        = ScheduleData::get_clients_headquarters( $this->schedule_list );
        $status       = ScheduleData::get_status_schedules( $this->schedule_list );
        $clients      = $query->select('clients_has_equipments.client_id')->distinct()->get()->toArray();
        $headquarters = $query->select('clients_has_equipments.headquarter_id')->distinct()->get()->toArray();
        $validator = true;

        foreach ( $status as $state ){
            if($state->status === 'Agendada' || $state->status == 'Agendada-Orden') $validator = false;
        }
        if( !$validator ) return toastr()->error('Los registros seleccionados ya han sido agendados o tienen una orden asignada. Verifique su planeador.','Error');

        if( count( $clients ) ===  0 ) return toastr()->error('Es necesario seleccionar al menos un registro para continuar.','error');
        elseif ( count( $clients ) > 1 ){
            return toastr()->error('Para continuar, selecciona los registros que pertenezcan al mismo cliente.','error');
        }elseif( count( $headquarters ) > 1 ){
            $this->dispatch('modal_validate_schedule',service:'event');
        }elseif( count( $headquarters ) === 1 ){

            $this->redirect_event();
        }

    }


    #[On('schedule_service_order')]
    public function schedule_redirect_order()
    {
        $clients  = ScheduleData::get_clients_headquarters( $this->schedule_list )
            ->select('clients_has_equipments.client_id')->distinct()->get()->toArray();
         $headquarter = ScheduleData::get_clients_headquarters( $this->schedule_list )
                         ->select('clients_has_equipments.headquarter_id')->distinct()->get()->toArray();

        $status       = ScheduleData::get_status_schedules( $this->schedule_list );

        $validator = true;

        foreach ( $status as $state ){
            if($state->status === 'Agendada' || $state->status == 'Agendada-Orden') $validator = false;
        }
        if( !$validator ) return toastr()->error('Los registros seleccionados ya han sido agendado. Verifique su planeador.','Error');

        if( count( $this->schedule_list ) ===  0 ) return toastr()->error('Es necesario seleccionar al menos un registro para continuar.','error');
        elseif(   count( $clients ) > 1 ){
            return toastr()->error('Para continuar, selecciona los registros que pertenezcan al mismo cliente.','error');
        }elseif ( count($headquarter) > 1 ){
            $this->dispatch('modal_validate_schedule',service:'order');
        }
        else{
            $this->redirect_order();
        }
    }

    #[On('redirect_event_form')]
    public function redirect_event()
    {
        $this->redirect('planner/create/schedule');
    }

    #[On('redirect_service_order')]
    public function redirect_order()
    {
        redirect()->route('admin.service-order.schedule.create');
    }

    #[Renderless]
    public function select_schedule()
    {
        HandelCache::put_cahce( $this->schedule_list,'schedule' );
    }

    public function search()
    {
        $this->resetPage();
    }

    public function show_error_msm(  )
    {
        $message = 'Esta acción no es permitida, el servicio tiene una orden de asignada.';
        return toastr()->error($message,'Error');
    }
}
