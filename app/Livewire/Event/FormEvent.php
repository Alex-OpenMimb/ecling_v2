<?php

namespace App\Livewire\Event;

use App\Helper\GeneralHelper;
use App\Models\Event;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveData;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Event\EventServices;
use App\Services\Schedule\ScheduleData;
use App\Services\Schedule\ServicesSchedule;
use App\Services\ServiceOrder\ServiceOrderHandle;
use App\Services\User\DataUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Closure;

class FormEvent  extends Component
{

    #[Locked]
    public $id;
    public $activity_id_array,$activity_type,$equipment_class,$client_name,$activity;
    public $activities_check_list = [], $date, $start_hour, $end_hour, $service_order;
    public $users_list = [], $day,$schedule_validator;

    public $user_checks_list = [];


    public function mount()
    {

        $this->set_url();
        $this->get_cache();
        if( $this->activity_id_array ){
            $this->user_checks_list = DataUser::get_users();
            $this->set_client_name();
            $this->set_activities();
        }


    }


    public function render()
    {
        return view('livewire.event.form');
    }


    public function updateOrStore()
    {
        //Start validations

        if( !$this->activity_id_array ) return toastr()->error('No has seleccionado un servicio, ingresa al modulo correspondiente.','Error');
        $this->validate();
        if( $this->start_hour === $this->end_hour ) return toastr()->error('La hora de inicio y la hora de finalización no pueden ser iguales','Error');
        if( $this->start_hour > $this->end_hour ) return toastr()->error('La hora de inicio debe ser anterior a la hora de finalización.','Error');
        if( count( $this->users_list ) == 0 )  return toastr()->error('Selecciona al menos un usuario para continuar.','Error');
        //End validations


        $user_id = auth()->user()->id;
        $this->day =  EventServices::get_day_week( $this->date );

        $find_id = ['id'=>$this->id];

        $data_event = [
            'date' =>  $this->date,
            'day' => $this->day,
            'start_hour' => $this->start_hour,
            'end_hour' => $this->end_hour,
            'activity' => $this->activity,
            'user_id' => $user_id,
        ];
        //Validate if some  user has events already.
        $data = [
            'start_hour' =>$this->start_hour,
            'end_hour' =>$this->end_hour
        ];

        if( $this->schedule_validator ){
            $user_name =   EventServices::validate_user_date( $this->users_list, $this->date, $data );

            if( count($user_name) > 0 ){
                $users =  GeneralHelper::concatenate($user_name);
                $message =  $users . ' tienen un evento programado para esta fecha y hora.' ;
                return  toastr()->error($message, 'Error');
            }
        }
        $service_order_id = null;
        $event = Event::updateOrCreate( $find_id, $data_event );
        $service_order_collection = null;
        //Create service order for this event.
        if( $this->service_order ) {
            $event->service_order = 1;
            $event->save();
            $data = [
                'activity'     => $event->activity,
                'observations' => NULL,
                'event'        =>  $event,
            ];

            $service_order_collection =  ServiceOrderHandle::store_service_order($data, $this->activity_id_array, $this->users_list );

        }
        EventServices::store_users_event( $event, $this->users_list );
        if($event->activity === 'Preventiva'){
            EventServices::store_schedules( $event, $this->activity_id_array, $service_order_collection );
            ServicesSchedule::scheduled( $this->activity_id_array, $event->service_order );
        }elseif( $event->activity === 'Correctiva' ){
            EventServices::store_event_corrective( $event, $this->activity_id_array );
            ClientEquipmentCorrectiveService::scheduled( $this->activity_id_array, $event->service_order );
        }


        $this->delete_cache();
        toastr()->success('Evento agendado con éxito','Felicitaciones!');
        redirect()->route('admin.planner');


    }




    protected function get_cache()
    {
        $key                    =  $this->get_key_cache();
        $this->activity_id_array = Cache::get($key);

    }

    protected function get_key_cache()
    {
        $user_id   = auth()->user()->id;
        return $this->activity_type .'-'. $user_id;
    }


    protected function set_url()
    {
        $this->activity_type = URL::current();
        $array_url           = explode('/', $this->activity_type);
        if( count( $array_url ) === 7 ) {
            $this->activity_type = end( $array_url );
        }

    }


    protected function set_client_name()
    {
        if( $this->activity_type === 'schedule' ){

            $this->client_name = ScheduleData::get_clients_headquarters( $this->activity_id_array )
                ->select('clients.name')->first()->name;
        }elseif( $this->activity_type === 'corrective' ){
            $this->client_name = ClientEquipmentCorrectiveData::get_client( $this->activity_id_array )->name;
        }


    }

    protected function set_activities()
    {
        if( $this->activity_type === 'schedule' ){
            $this->activity = 'Preventiva';
            $this->set_schedule();
        }elseif( $this->activity_type === 'corrective' ){
            $this->activity = 'Correctiva';
            $this->set_corrective();
        }

    }

    protected function set_schedule()
    {
        $this->activities_check_list = ScheduleData::get_activities_equipment( $this->activity_id_array )
            ->select('schedules.id',
                'preventive_routines.name',
                'clients_has_equipments.internal_id',
                'equipments.name as equipment_name',
                'clients_has_equipments.id as client_has_equipment_id')->get()
            ->toArray();

    }


    protected function set_corrective()
    {
        $this->activities_check_list  = ClientEquipmentCorrectiveData::get_activities_equipments( $this->activity_id_array )
            ->distinct('clients_has_equipments.id')
            ->select('clients_equipments_correctives.corrective_service_id as id',
                'clients_has_equipments.internal_id',
                'equipments.equipment_class_id',
                'equipments.name as equipment_name',
                'clients_has_equipments.id as client_has_equipment_id')->get()->toArray();


    }


    protected function delete_cache()
    {
        $key = $this->get_key_cache();
        Cache::forget( $key );
    }

    protected function switch_activity( $event, $service_order_id )
    {

    }

    protected function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                function (string $attribute, mixed $value, Closure $fail) {
                    $date = Carbon::parse($value);
                    if (!$date->isToday() && $date->isBefore(Carbon::now())) {
                        $fail('La fecha debe ser hoy o una fecha futura.');
                    }
                },
            ],


        ];
    }

    protected function messages()
    {
        return [
            'date.required' => 'La fecha es requerida',

        ];
    }
}
