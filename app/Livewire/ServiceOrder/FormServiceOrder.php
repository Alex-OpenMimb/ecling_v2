<?php

namespace App\Livewire\ServiceOrder;

use App\Helper\GeneralHelper;
use App\Models\Event;
use App\Models\ScheduleEvent;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveData;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Event\EventServices;
use App\Services\Schedule\ScheduleData;
use App\Services\Schedule\ServicesSchedule;
use App\Services\ServiceOrder\ServiceOrderHandle;
use App\Services\User\DataUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class FormServiceOrder extends Component
{

    public $activity_type, $users = [], $client_name, $activity_id, $users_list = [];
    public $services = [], $activity, $equipment_class_id,$equipment_class,$start_hour,$end_hour;
    public $observations,$schedule_validator, $previous_url, $event_cache_key;


    public function mount()
    {
        $this->check_url();
        $this->set_previous_url();

        if( $this->previous_url === 'planner' ) $this->set_user_from_planner();
        $this->get_cache();
        if( $this->activity_id ){
            $this->get_users();
            $this->switch_activity();
        }

    }

    public function render()
    {
        return view('livewire.serviceOrder.form');
    }

    public function store()
    {
        if( !$this->activity_id ) return toastr()->error('No has seleccionado un servicio, ingresa al modulo de correspondiente.','Error');
        if( !$this->start_hour || !$this->end_hour ) return toastr()->error('Seleccione un rango de hora valido.','Error');
        if( $this->start_hour === $this->end_hour ) return toastr()->error('La hora de inicio y la hora de finalización no pueden ser iguales','Error');
        if( $this->start_hour > $this->end_hour ) return toastr()->error('La hora de inicio debe ser anterior a la hora de finalización.','Error');
        if( count( $this->users_list ) == 0 )  return toastr()->error('Selecciona al menos un usuario para continuar.','Error');
        $this->validate();

        //Validate if some  user has events already.
        $date = Carbon::now()->format('Y-m-d');
        $data = [
            'start_hour' =>$this->start_hour,
            'end_hour' =>$this->end_hour
        ];

        if( $this->schedule_validator ){
            $user_name =   EventServices::validate_user_date( $this->users_list, $date, $data  );

            if( count($user_name) > 0 ){
                $users =  GeneralHelper::concatenate($user_name);
                $message =  $users . ' tienen un evento programado para hoy en el rango de hora seleccionado.' ;
                return  toastr()->error($message, 'Error');
            }
        }
        //End validations
        $event = EventServices::create_event_for_order( $this->start_hour, $this->end_hour,$this->activity );
        $data_service = [
            'activity'     =>  $this->activity,
            'observations' =>   $this->observations,
            'event'        =>      $event,
        ];
        $service_order_collection = ServiceOrderHandle::store_service_order( $data_service, $this->activity_id, $this->users_list );

        if($this->activity_type === 'schedule' ){
            EventServices::store_schedules( $event, $this->activity_id, $service_order_collection );
            ServicesSchedule::scheduled( $this->activity_id, $event->service_order );

        }elseif( $this->activity_type === 'corrective' ){
            EventServices::store_event_corrective( $event, $this->activity_id );
            ClientEquipmentCorrectiveService::scheduled( $this->activity_id, $event->service_order );
        }

        EventServices::store_users_event( $event, $this->users_list );
        //Clear cache
        $key =  $this->get_key_cache();
        Cache::forget($key);

        toastr()->success('Orden de servicio creada con éxito.','Felicitaciones.');
        redirect()->route('admin.service-order');
    }



    public function store_from_planner()
    {
        if( count( $this->users_list ) == 0 )  return toastr()->error('Selecciona al menos un usuario para continuar.','Error');
        $this->validate();
        $this->set_event_cache_key();
        $event_id = Cache::get( $this->event_cache_key );
        $event = Event::find( $event_id );
        $data = [
            'activity'=>$this->activity,
            'observations'=> $this->observations,
            'event'=>   $event
        ];
        $service_order_collection = ServiceOrderHandle::store_service_order( $data, $this->activity_id, $this->users_list );

        foreach ($service_order_collection as $index => $service_order){
            ScheduleEvent::where('event_id', $event_id)->update([
                'service_order_id' => $service_order->id
        ]);
        }

        $this->update_event();
        toastr()->success('Orden de servicio creada con éxito.','Felicitaciones.');
        redirect()->route('admin.planner');
    }

    protected function set_previous_url()
    {
        $this->activity_type = URL::current();
        $array_url         = explode('/', $this->activity_type);
        $this->activity_type = end( $array_url );

    }

    protected function set_event_cache_key()
    {
        $user_id   = auth()->user()->id;

        switch( $this->activity_type )
        {
            case 'schedule':
                $this->event_cache_key = 'event-schedule-'.$user_id;
                break;

            case 'corrective':
                $this->event_cache_key = 'event-corrective-'.$user_id;
                break;
        }

    }

    protected function check_url()
    {
        $previous_url =  URL::previous();
        $previous_url = explode( '/',$previous_url );
        $this->previous_url = end( $previous_url );

    }

    protected function get_users()
    {
        if( $this->previous_url === 'planner' ){
            $this->users = DataUser::get_users_by_id( $this->users_list );
        } else{
            $this->users = DataUser::get_users();
        }


    }

    protected function get_key_cache()
    {
        $user_id   = auth()->user()->id;
        return $this->activity_type .'-'. $user_id;
    }

    protected function get_cache()
    {
        $key       =  $this->get_key_cache();
        $this->activity_id = Cache::get($key);

    }


    protected function set_user_from_planner()
    {
        $user_id   = auth()->user()->id;
        $key       = 'users-planner-'.$user_id;
        $this->users_list = Cache::get( $key );

    }

    protected function switch_activity()
    {
        if($this->activity_type === 'schedule' ){
            $this->activity = 'Preventiva';
            $this->client_name = ScheduleData::get_client_by_schedule($this->activity_id)
                ->select('clients.name')->first()->name;
            $this->services  = ScheduleData::get_activities_equipment( $this->activity_id )
                ->select( 'clients_has_equipments.id as equipment_id',
                    'clients_has_equipments.internal_id',
                    'preventive_routines.name',
                    'equipments.equipment_class_id',
                    'schedules.id as schedule_id')->get()->toArray();


        }elseif ($this->activity_type === 'corrective'){
            $this->activity = 'Correctiva';
            $this->client_name =  ClientEquipmentCorrectiveData::get_client( $this->activity_id )->name;
            $this->services  = ClientEquipmentCorrectiveData::get_activities_equipments($this->activity_id)
               ->distinct('clients_has_equipments.id')
                ->select('clients_has_equipments.internal_id',
                    'clients_has_equipments.id as equipment_id',
                    'equipments.equipment_class_id',
                      'equipments.name')->get()
                ->toArray();
        }
    }

    protected function update_event()
    {
        $user_id   = auth()->user()->id;
        $service_order = 1;
        $key = NULL;
        $activity_key = NULL;
        $user_planner_key = 'users-planner-'.$user_id;
        if( $this->activity_type === 'schedule' ){
            ServicesSchedule::scheduled( $this->activity_id, $service_order );
            $key = 'event-schedule-'.$user_id;
            $activity_key = 'schedule-'.$user_id;

        }elseif ( $this->activity_type === 'corrective' ){
            $key = 'event-corrective-'.$user_id;
            $activity_key = 'corrective-'.$user_id;
            ClientEquipmentCorrectiveService::scheduled( $this->activity_id, $service_order );
        }

        $event_id = Cache::get($key);
        DB::table('events')->where('id',$event_id)->update([
            'service_order' => 1
        ]);
        Cache::forget( $key );
        Cache::forget( $activity_key );
        Cache::forget( $user_planner_key );
    }


    public function rules()
    {
        return [
            'observations' => [
                'nullable',
                'string',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 10 ){
                        $fail('La observación debe contener la menos 10 caracteres');
                    }
                    if( strlen( $value ) > 200 ){
                        $fail('La observación debe contener un máximo de 200 caracteres');
                    }
                }
            ],
        ];
    }
}
