<?php

namespace App\Livewire\ClientEquipmentCorrective;

use App\Helper\GeneralHelper;
use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveData;
use App\Services\User\HandelCache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableEquipmentCorrective  extends Component
{


    use WithPagination;

    public $counter = 1, $amount = 10, $query, $equipment_class_id, $client_name, $corrective_list=[];
    public $search_users;




    public function mount()
    {
        $this->search_users =  GeneralHelper::set_auth_users();
        HandelCache::deleted_cache('corrective');
    }
    #[On('update_corrective')]
    public function render()
    {
        $correctives = $this->get_correctives();
        return view('livewire.clientEquipmentCorrective.datatable',['correctives'=>$correctives]);
    }



    protected function get_correctives()
    {
        $queries = trim($this->query);
        return  CorrectiveService::select(
            'clients.name as client_name',
            'headquarters.name as headquarter_name',
            'clients_has_equipments.internal_id',
            'corrective_services.status',
            'corrective_services.id',
            'clients_equipments_correctives.client_has_equipment_id',
            DB::raw("GROUP_CONCAT(corrective_activities.activity SEPARATOR ', ') as related_activities")
        )
            ->join('clients_equipments_correctives', 'corrective_services.id', '=', 'clients_equipments_correctives.corrective_service_id')
            ->join('clients_has_equipments', 'clients_equipments_correctives.client_has_equipment_id', '=', 'clients_has_equipments.id')
            ->join('corrective_activities', 'clients_equipments_correctives.corrective_activity_id', '=', 'corrective_activities.id')
            ->join('clients', 'clients_has_equipments.client_id', '=', 'clients.id')
            ->join('equipments', 'clients_has_equipments.equipment_id', '=', 'equipments.id')
            ->join('headquarters', 'clients_has_equipments.headquarter_id', '=', 'headquarters.id')
            ->whereIn('corrective_services.user_id', $this->search_users)
            ->distinct('clients_equipments_correctives.client_has_equipment_id')
            ->where(function ($query) use ($queries) {
                $query->orWhere('clients.name', 'like', '%' . $queries . '%')
                    ->orWhere('headquarters.name', 'like', '%' . $queries . '%')
                    ->orWhere('corrective_services.status', 'like', '%' . $queries . '%')
                    ->orWhere('clients_has_equipments.serial', 'like', '%' . $queries . '%');
            })
            ->groupBy(
                'clients.name',
                'headquarters.name',
                'clients_has_equipments.internal_id',
                'corrective_services.status',
                'corrective_services.id',
                'clients_equipments_correctives.client_has_equipment_id'
            )
            ->orderBy('corrective_services.id', 'desc')
            ->simplePaginate($this->amount);

    }


    public function show_error_delete($action)
    {
        if( $action === 'edit' )  $message = 'Esta actividad está agengda, no es posible editarla.';
        elseif( $action === 'delete' ){
            $message = 'Esta actividad está agengda, no es posible eliminarla.';
        }elseif( $action === 'closed' ){
            $message = 'Esta actividad ha sido ejecutada, no es posible eliminarla.';
        }elseif ( $action === 'closed-edit' ){
            $message = 'Esta actividad ha sido ejecutada, no es posible editarla.';
        }
        return toastr()->error($message,'Error');
    }


    public function redirect_edit( $corrective_service_id )
    {
        $corrective_service_id = Crypt::encryptString( $corrective_service_id );
        redirect()->route('admin.corrective-management.edit',['corrective_service_id' =>  $corrective_service_id]);
    }

    public function select_corrective()
    {
        HandelCache::put_cahce( $this->corrective_list,'corrective' );
    }

    #[On('validate_corrective')]
    public function corrective_redirect_event()
    {
        $query        = ClientEquipmentCorrectiveData::get_equipments_by_corrective( $this->corrective_list );
        $clients      = $query->select('clients_has_equipments.client_id')
            ->distinct()->get()->toArray();
        $headquarters = $query->select('clients_has_equipments.headquarter_id')
            ->distinct()->get()->toArray();
        $status       = ClientEquipmentCorrectiveData::get_status_corrective( $this->corrective_list );
        $validator_schedule = true;
        $closed_validator = true;
        foreach ( $status as $state ){
            if($state->status == 'Agendado' || $state->status == 'Agendado-Orden'  ) $validator_schedule = false;
            if( $state->status ==='Cerrado' ) $closed_validator = false;
        }

        if( !$validator_schedule ) return toastr()->error('Los serivicios seleccionados ya han sido agendados o tienen una orden asignada. Verifique su planeador.','Error');
        if( !$closed_validator ) return toastr()->error('EL servicio seleccionado ya ha sido ejecutado.','Error');

        if( count( $clients ) ===  0 ) return toastr()->error('Es necesario seleccionar al menos un servicio para continuar.','Error');
        elseif ( count( $clients ) > 1 ){
            return toastr()->error('Para continuar, selecciona los servicios que pertenezcan al mismo cliente.','Error');
        }elseif( count( $headquarters ) > 1 ){
            return   $this->dispatch('modal_validate_corrective',service:'event');
        }elseif( count( $headquarters ) === 1 ){

            $this->redirect_event();
        }


    }

    #[On('validate_corrective_service_order')]
    public function corrective_redirect_order()
    {
        $query        = ClientEquipmentCorrectiveData::get_equipments_by_corrective( $this->corrective_list );
        $clients      = $query->select('clients_has_equipments.client_id')
            ->distinct()->get()->toArray();
        $headquarters = $query->select('clients_has_equipments.headquarter_id')
            ->distinct()->get()->toArray();;

        $service_orders = ClientEquipmentCorrectiveData::get_order_by_activity( $this->corrective_list );

        if( count( $this->corrective_list ) ===  0 ) return toastr()->error('Es necesario seleccionar al menos un registro para continuar.','error');
        elseif(   count( $clients ) > 1 ){
            return toastr()->error('Para continuar, selecciona los registros que pertenezcan al mismo cliente.','error');
        }elseif( count( $headquarters ) > 1 ){
            return  $this->dispatch('modal_validate_corrective',service:'order');
        }

        $status    = ClientEquipmentCorrectiveData::get_status_corrective( $this->corrective_list );
        $schedule_validator = true;
        $closed_validator = true;
        foreach ( $status as $state ){
            if($state->status == 'Agendado' || $state->status == 'Agendado-Orden'  ) $schedule_validator = false;
            if( $state->status ==='Cerrado' ) $closed_validator = false;
        }
        if( !$closed_validator ) return toastr()->error('EL servicio seleccionado ya ha sido ejecutado.','Error');


        $validator =   array_filter($service_orders,function ($value) {
            return $value;
        });

        if( count( $validator )  === 0 && $schedule_validator ){
            $this->redirect_order();
        }else{
            return toastr()->error('Para continuar, selecciona un registro sin orden de servicios o no agendado.','error');
        }

    }

    #[On('redirect_event_form_corrective')]
    public function redirect_event()
    {
      redirect()->route('admin.planner.corrective');
    }

    #[On('redirect_order_form_corrective')]
    public function redirect_order()
    {
        redirect()->route('admin.service-order.corrective.create');
    }


    #[On('delete_corrective')]
    public function delete(ClientsEquipmentsCorrective $clients_equipments_correctives)
    {
        $clients_equipments_correctives->delete();
        toastr()->success('Registro eliminado con éxito!', 'Felicitaciones');


    }

    public function search()
    {
        $this->resetPage();
    }


}
