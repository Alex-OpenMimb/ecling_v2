<?php

namespace App\Livewire\ClientEquipmentCorrective;

use App\Models\ClientsEquipmentsCorrective;
use App\Models\CorrectiveService;
use App\Models\GeneralReport;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Cache;
use LivewireUI\Modal\ModalComponent;


class ExistingOrder  extends ModalComponent
{
    public $user_id, $validator, $error_msm, $general_report_list,$corrective_list;
    public $client_equipment_id, $general_report_id, $service_order_id;


    public function mount()
    {
        $this->user_id = auth()->user()->id;
        $this->error_msm = 'Por favor, selecciona  un registro para continuar.';
        $this->validate_equipment();
        if ($this->validator) $this->get_general_report();
    }

    public function render()
    {
        return view('livewire.clientEquipmentCorrective.existingOrder');
    }


    public function assign()
    {
        $this->validate();

        $this->service_order_id =  GeneralReport::where('id',$this->general_report_id)->select('service_order_id')
            ->first()->service_order_id;
        CorrectiveService::whereIn('id',$this->corrective_list)->update([
            'service_order_id' => $this->service_order_id,
            'status' => 'Agendado-Orden'
        ]);
        GeneralReport::where('id',$this->general_report_id)->update([
            'corrective' => 1
        ]);
        ServiceOrder::where('id',$this->service_order_id)->update([
            'activity' =>'Mixta'
        ]);


        $this->dispatch('update_corrective');
        $this->dispatch('reload');
        $this->closeModal();
        $key = 'corrective-'.$this->user_id;
        Cache::forget($key);


    }


    protected function validate_equipment()
    {
        $key = 'corrective-'.$this->user_id;
        if( Cache::has($key) ) $this->corrective_list = Cache::get( $key );
        else{
            $this->corrective_list = [];
        }

        $this->client_equipment_id = ClientsEquipmentsCorrective::join('corrective_services','clients_equipments_correctives.corrective_service_id','corrective_services.id')
            ->whereIn('corrective_services.id',$this->corrective_list)
            ->where('corrective_services.status','Abierto')
            ->select('clients_equipments_correctives.client_has_equipment_id')
            ->distinct('clients_equipments_correctives.client_has_equipment_id')
            ->get()->toArray();

        if( count($this->client_equipment_id) === 1 ) $this->validator = true;
        elseif( count($this->client_equipment_id) === 0 || count($this->client_equipment_id) > 1  ){
            $this->validator = false;
        }


    }

    protected function get_general_report()
    {
        $this->general_report_list = GeneralReport::whereIn('client_has_equipment_id',$this->client_equipment_id)
            ->where('stored',0)
            ->where('preventive',1)
            ->where('corrective',0)
            ->select('id','serial')->get();
    }



    public function rules()
    {

        return [
            'general_report_id' => 'required|exists:general_reports,id'
        ];
    }


    public function messages()
    {
        return [
            'general_report_id.required' => 'El reporte es requerido.',
            'general_report_id.exists' => 'El reporte no existe en los registros.',

        ];
    }
}
