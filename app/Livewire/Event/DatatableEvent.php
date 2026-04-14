<?php

namespace App\Livewire\Event;

use App\Helper\GeneralHelper;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\ScheduleEvent;
use App\Models\ServiceOrder;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Schedule\ServicesSchedule;
use App\Services\User\HandelCache;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableEvent extends Component
{

    use WithPagination;
    public $counter = 1,$amount = 10, $query, $search_users;

    public function mount()
    {
        $this->search_users =  GeneralHelper::set_auth_users();
        HandelCache::deleted_cache('schedule');

    }

    #[On('reload_events')]
    public function render()
    {
        $events = $this->get_events()
            ->orderBy('events.date')
            ->orderBy('events.start_hour')
            ->simplePaginate($this->amount);
        return view('livewire.event.datatable',['events'=>$events]);
    }


    protected function get_events()
    {
        $searchUsers = $this->search_users ?? [];
        if ($searchUsers === []) {
            $searchUsers = auth()->check() ? [auth()->id()] : DB::table('users')->pluck('id')->all();
        }

        $queries = trim($this->query);

        $visitFirst = DB::table('visits')
            ->select('event_id', DB::raw('MIN(id) as id'))
            ->groupBy('event_id');

        $query_event = Event::select(
            'events.id',
            'events.day',
            'events.date',
            'events.start_hour',
            'events.end_hour',
            'schedules.client_has_equipment_id as cl_eq_schedule',
            'clients_equipments_correctives.client_has_equipment_id as cl_eq_corrective',
            'events.activity',
            'corrective_services.service_order_id as corrective_order_id',
            'schedules_has_service_orders.service_order_id as schedule_order_id',
            'events.service_order',
            'events.user_id',
            'events.closed',
            'visits.id as visit_id',
            'visits.client_name as visit_client_name',
            'visits.headquarter_name as visit_headquarter_name',
            'visits.observations as visit_observations',
            'visits.report as visit_report',
            'visits.status as visit_status',
            'visits.client_id as visit_client_id',
            'visits.headquarter_id as visit_headquarter_id',
            'visits.visit_reason_id as visit_reason_id',
        )->leftJoin('corrective_services', 'events.id', '=', 'corrective_services.event_id')
            ->leftJoin('clients_equipments_correctives', 'corrective_services.id', '=', 'clients_equipments_correctives.corrective_service_id')
            ->leftJoin('schedules_has_events', 'events.id', '=', 'schedules_has_events.event_id')
            ->leftJoin('schedules', 'schedules_has_events.schedule_id', '=', 'schedules.id')
            ->leftJoin('schedules_has_service_orders', 'schedules.id', '=', 'schedules_has_service_orders.schedule_id')
            ->leftJoinSub($visitFirst, 'visit_first', 'events.id', '=', 'visit_first.event_id')
            ->leftJoin('visits', 'visits.id', '=', 'visit_first.id')
            ->groupBy(
                'events.id',
                'events.day',
                'events.date',
                'events.start_hour',
                'events.end_hour',
                'schedules.client_has_equipment_id',
                'clients_equipments_correctives.client_has_equipment_id',
                'events.activity',
                'corrective_services.service_order_id',
                'schedules_has_service_orders.service_order_id',
                'events.service_order',
                'events.user_id',
                'events.closed',
                'visits.id',
                'visits.client_name',
                'visits.headquarter_name',
                'visits.observations',
                'visits.report',
                'visits.status',
                'visits.client_id',
                'visits.headquarter_id',
                'visits.visit_reason_id',
            );

        return DB::query()
            ->fromSub($query_event, 'events')
            ->leftJoin('clients_has_equipments', function (JoinClause $join) {
                $join->on('clients_has_equipments.id', '=', 'events.cl_eq_schedule')
                    ->orOn('clients_has_equipments.id', '=', 'events.cl_eq_corrective')
                    ->orOn(function (JoinClause $join) {
                        $join->whereNotNull('events.visit_client_id')
                            ->whereColumn('clients_has_equipments.client_id', 'events.visit_client_id');
                    });
            })
            ->where(function ($q) {
                $q->whereNull('clients_has_equipments.id')
                    ->orWhereNull('clients_has_equipments.deleted_at');
            })
            ->leftJoin('clients', function (JoinClause $join) {
                $join->on('clients_has_equipments.client_id', '=', 'clients.id')
                    ->orOn('clients.id', '=', 'events.visit_client_id');
            })->leftJoin('service_orders', function (JoinClause $join) {
            $join->on('events.corrective_order_id', '=', 'service_orders.id')
                ->orOn('events.schedule_order_id', '=', 'service_orders.id');
        })->leftJoin('events_has_users', 'events.id', '=', 'events_has_users.event_id')
            ->leftJoin('visits_users', 'events.visit_id', '=', 'visits_users.visit_id')
            ->leftJoin('users as visit_assigned_user', 'visit_assigned_user.id', '=', 'visits_users.user_id')
            ->where('events.closed', false)
            ->where(function ($query) use ($searchUsers) {
                $query->whereIn('events.user_id', $searchUsers)
                    ->orWhereIn('events_has_users.user_id', $searchUsers)
                    ->orWhereIn('visits_users.user_id', $searchUsers);
            })->where(function ($query) use ($queries) {
                $query->where('events.activity', 'like', '%' . $queries . '%')
                    ->orWhere('events.date', 'like', '%' . $queries . '%')
                    ->orWhere('events.day', 'like', '%' . $queries . '%')
                    ->orWhere('clients.name', 'like', '%' . $queries . '%')
                    ->orWhere('service_orders.serial', 'like', '%' . $queries . '%')
                    ->orWhere('events.visit_client_name', 'like', '%' . $queries . '%')
                    ->orWhere('events.visit_headquarter_name', 'like', '%' . $queries . '%')
                    ->orWhere('events.visit_observations', 'like', '%' . $queries . '%')
                    ->orWhere('visit_assigned_user.name', 'like', '%' . $queries . '%');
            })->distinct()
            ->select(
                DB::raw('COALESCE(clients_has_equipments.client_id, events.visit_client_id) as client_id'),
                'events.id',
                'events.user_id',
                'events.day',
                'events.date',
                'events.start_hour',
                'events.end_hour',
                'clients.name as client_name',
                'events.activity',
                'events.service_order',
                'events.visit_id',
                'events.visit_client_name',
                'events.visit_headquarter_name',
                'events.visit_observations',
                'events.visit_report',
                'events.visit_status',
                'events.visit_client_id',
                'events.visit_headquarter_id',
                'events.visit_reason_id',
                DB::raw("GROUP_CONCAT(DISTINCT IF(service_orders.status = 'Abierta', service_orders.serial, NULL) SEPARATOR ', ') as serial"),
                DB::raw('GROUP_CONCAT(DISTINCT visit_assigned_user.name ORDER BY visit_assigned_user.name SEPARATOR \', \') as visit_users_names')
            )
            ->groupBy(
                DB::raw('COALESCE(clients_has_equipments.client_id, events.visit_client_id)'),
                'events.id',
                'events.user_id',
                'events.day',
                'events.date',
                'events.start_hour',
                'events.end_hour',
                'clients.name',
                'events.activity',
                'events.service_order',
                'events.visit_id',
                'events.visit_client_name',
                'events.visit_headquarter_name',
                'events.visit_observations',
                'events.visit_report',
                'events.visit_status',
                'events.visit_client_id',
                'events.visit_headquarter_id',
                'events.visit_reason_id',
            );

    }

    #[On('delete_event')]
    public function delete_event(Event  $event )
    {
        if( $event->activity === 'Preventiva' ){
            ServicesSchedule::delete_scheduled_event( $event );

        }elseif( $event->activity === 'Correctiva' ){
            ClientEquipmentCorrectiveService::delete_corrective_event( $event );
        }

        $this->dispatch('reload_events');

    }


    public function redirect_general_report_events( $service_order_serial )
    {
        $service_order_id =  ServiceOrder::where('serial', $service_order_serial)->select('id')
            ->first()->id;
        $service_order_id = Crypt::encryptString($service_order_id);
        redirect()->route('admin.general-reports',['service_order_id'=>$service_order_id]);
    }

    public function create_order_by_service(  Event  $event )
    {
        $activity = $event->activity;
        $event_id = $event->id;
        $this->set_users( $event_id );
        if($activity === 'Preventiva'){

            $schedule_list =  ScheduleEvent::where('event_id',$event_id)->select('schedule_id')
                ->pluck('schedule_id')->toArray();

            HandelCache::put_cahce( $schedule_list,'schedule' );
            HandelCache::put_event_cache('schedule',$event_id);
            redirect()->route('admin.service-order.schedule.create');

        }elseif($activity === 'Correctiva' ){

            $corrective_list = CorrectiveService::where('event_id',$event_id)->select('id')
                ->pluck('id')->toArray();
            HandelCache::put_cahce( $corrective_list,'corrective' );
            HandelCache::put_event_cache('corrective',$event_id);
            redirect()->route('admin.service-order.corrective.create');
        }
    }



    protected function set_users( $event_id)
    {
        $user_list =  EventUser::where('event_id',$event_id)->select('user_id')
            ->pluck('user_id')->toArray();
        HandelCache::put_users_cache( $user_list);
    }


    public function error_message_event( $action )
    {
        if( $action === 'delete' )   $message = 'Este evento tiene asignada una orden de servicio, por lo que no es posible eliminarlo.';
        return toastr()->error($message,'Error');

    }

    public function search()
    {
        $this->resetPage();
    }


}
