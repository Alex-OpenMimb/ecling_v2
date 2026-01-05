<?php

namespace App\Livewire\ClientEquipmentCorrective;

use App\Models\Client;
use App\Models\ClientEquipmentCorrective;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveActivity;
use App\Models\CorrectiveService;
use App\Models\EquipmentClass;
use App\Models\Headquarter;
use App\Services\ClientEquipment\DataClientEquipment;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\CorrectiveActivity\CorrectiveActivityService;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormEquipmentCorrective   extends Component
{


    public $clients_list = [], $client_id, $equipment_classes_list = [],$equipment_class_id, $headquarters_list= [];

    public $equipment_clients_check_input = [],$activities_check_input = [], $headquarter_id, $client_restart ;

    public $corrective_list = [], $equipment_list = [], $action;
    public $client_has_equipment_id,$corrective_activity_id, $client_equipment_corrective;
    #[Locked]
    public $id;

    public function mount( $corrective_service_id = null  )
    {
        if( $corrective_service_id ){
            $corrective_service_id = Crypt::decryptString( $corrective_service_id );
            $corrective_service = CorrectiveService::find( $corrective_service_id );
            $this->fill(
                $corrective_service->only('id')
            );

            $this->action = 1;
            $this->get_stored_client();
            $this->equipment_class_id  = ClientsEquipmentsCorrective::join('corrective_services','clients_equipments_correctives.corrective_service_id','corrective_services.id')
                ->where('corrective_services.id', $this->id)->select('clients_equipments_correctives.equipment_class_id')
                ->first()->equipment_class_id;

            $this->equipment_clients_check_input =  DataClientEquipment::get_equipment( $this->client_has_equipment_id )->select('brands.name as brand_name',
                'locations.name as location_name',
                'headquarters.name as headquarter_name',
                'equipment_classes.name as class_name',
                'volts.volt_measurement',
                'volts.unit as volt_unit',
                'amperes.amperage_measurement',
                'amperes.unit as ampere_unit',
                'clients_has_equipments.internal_id',
                'equipment_models.model as equipment_model',
                'clients_has_equipments.id',
                'equipments.name')
                ->get()
                ->toArray();

            $this->activities_check_input    = $this->get_corrective_activities();
            $this->get_stored_corrective();
            $this->equipment_list[]          = $this->client_has_equipment_id;
        }

        $this->clients_list = Client::getClients()
            ->orderBY('name', 'asc')->get();
        $this->equipment_classes_list =  EquipmentClass::getEquipmentClasses()->get();
    }




    public function updateOrStore()
    {
        //Validate activities and equipment incoming to store data.
        if (count($this->corrective_list) === 0 || count($this->equipment_list) === 0) {
            return toastr()->error('Seleccionar al menos un equipo o actividad.', 'Error');
        }

        //Validate if any equipment has activity corrective already.
        $validator = ClientEquipmentCorrectiveService::validate_available_equipment( $this->equipment_list, $this->corrective_list, $this->id );
        if( count( $validator ) > 0 ){
            return  toastr()->error('Los equipos que seleccionaste ya cuentan con servicios correctivos.','Error');
        }

        $this->equipment_list   = array_unique( $this->equipment_list  );
        $this->corrective_list  = array_unique( $this->corrective_list );
        if( $this->id ){
            $this->update();
        }else{
            $this->store();
            $this->assign_activities();

        }

        $message = $this->id ? 'actualizado' : 'creado';
        toastr()->success('Los datos se han ' . $message . ' con éxito!', 'Felicitaciones');
        return redirect()->route('admin.corrective-management');


    }



    public function render()
    {
        return view('livewire.clientEquipmentCorrective.form');
    }

    public function  updatedEquipmentClassId( $property )
    {
        $this->equipment_clients_check_input = [];
        $this->activities_check_input = [];
        $this->headquarter_id = NULL;
        $this->equipment_class_id = $property;
        $this->client_restart = true;


    }


    public function updatedClientId()
    {
        $this->equipment_clients_check_input = [];
        $this->headquarters_list = Headquarter::getHeadquarterByClient( $this->client_id )->get();
    }

    public function updatedHeadquarterId()
    {
        $this->client_restart = false; //Flag to restart the client select
        $headquarter_id = $this->headquarter_id;
        $equipment_class_id = $this->equipment_class_id;
        $this->equipment_clients_check_input = $this->get_equipments( $headquarter_id, $equipment_class_id  );

        $this->activities_check_input = $this->get_corrective_activities();
    }


    protected function get_equipments( $headquarter_id, $equipment_class_id )
    {

        return DataClientEquipment::get_equipment_by_headquarter( $headquarter_id,$equipment_class_id )
            ->select('brands.name as brand_name',
                'locations.name as location_name',
                'headquarters.name as headquarter_name',
                'equipment_classes.name as class_name',
                'volts.volt_measurement',
                'volts.unit as volt_unit',
                'amperes.amperage_measurement',
                'amperes.unit as ampere_unit',
                'clients_has_equipments.internal_id',
                'equipment_models.model as equipment_model',
                'clients_has_equipments.id',
                'equipments.name')
            ->get()
            ->toArray();
    }

    protected function  get_corrective_activities()
    {
        return CorrectiveActivity::getCorrectiveActivitiesByClass( $this->equipment_class_id )
            ->get()
            ->toArray();
    }


    protected function  store()
    {

        $equipments = $this->equipment_list;
        $correctives = $this->corrective_list;
        $user_id = auth()->user()->id;

        foreach ($equipments as $equipment_id) {
            $corrective_service =     CorrectiveService::create([
                'user_id' => $user_id,
            ]);
            foreach ($correctives as $corrective_id) {
                ClientsEquipmentsCorrective::create([
                    'client_has_equipment_id'=> $equipment_id,
                    'corrective_activity_id' => $corrective_id,
                    'equipment_class_id'     => $this->equipment_class_id,
                    'corrective_service_id'  => $corrective_service->id
                ]);
            }
        }
    }

    protected function assign_activities()
    {
        $correctives = $this->corrective_list;

        foreach ( $correctives as $key => $corrective_id  )
        {
            CorrectiveActivityService::assign( $corrective_id );
        }
    }

    protected function get_stored_client()
    {
        $corrective = ClientsEquipmentsCorrective::join('clients_has_equipments',
            'clients_equipments_correctives.client_has_equipment_id','=','clients_has_equipments.id')
            ->where('clients_equipments_correctives.corrective_service_id', $this->id)
            ->select('clients_has_equipments.client_id','clients_has_equipments.headquarter_id',
                      'clients_has_equipments.id as client_has_equipment_id')->first();

        $this->client_id = $corrective->client_id;
        $this->headquarters_list = Headquarter::getHeadquarterByClient($this->client_id)->get();
        $this->headquarter_id  = $corrective->headquarter_id;
        $this->client_has_equipment_id  = $corrective->client_has_equipment_id;

    }

    protected function get_stored_corrective()
    {
        $correctives = ClientsEquipmentsCorrective::where('corrective_service_id', $this->id)->select('corrective_activity_id')
            ->get()->pluck('corrective_activity_id')->toArray();
        foreach ( $correctives as $index => $corrective_id ){
            $this->corrective_list[] = $corrective_id;
        }
    }



    protected function update()
    {
        $equipments = $this->equipment_list;
        $correctives = $this->corrective_list;
        ClientsEquipmentsCorrective::where('corrective_service_id',$this->id)->delete();
        foreach ($equipments as $equipment_id) {

            foreach ($correctives as $corrective_id) {
                ClientsEquipmentsCorrective::create([
                    'client_has_equipment_id'=> $equipment_id,
                    'corrective_activity_id' => $corrective_id,
                    'equipment_class_id'     => $this->equipment_class_id,
                    'corrective_service_id'  => $this->id
                ]);
            }
        }
    }




}
