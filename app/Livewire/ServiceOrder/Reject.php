<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use App\Services\ClientEquipmentCorrective\ClientEquipmentCorrectiveService;
use App\Services\Event\EventServices;
use App\Services\GeneralReport\GeneralReportService;
use App\Services\Schedule\ServicesSchedule;
use App\Services\ServiceOrder\ServiceOrderHandle;
use LivewireUI\Modal\ModalComponent;

class Reject  extends ModalComponent
{

    public ServiceOrder $service_order;
    public $serial, $rejected_user_id, $status,$observations_status, $service_order_id;
    public $activity;

    public $id;


    public function mount( ServiceOrder $service_order )
    {
        $this->rejected_user_id = auth()->user()->id;
        $this->service_order = $service_order;
        $this->fill(
            $service_order->only('observations_status','serial','id','activity')
        );
        $this->service_order_id  = $service_order->id;

    }

    public function render()
    {
        return view('livewire.serviceOrder.reject');
    }

    public function reject()
    {
          $this->validate();

        $closed_reports = GeneralReportService::validate_closed_report(   $this->service_order_id );
            if( $this->status === 'Rechazada' && !$closed_reports ){
                 return toastr()->error('La orden no puede ser recachazada, hay reportes diligenciados','Error');
            }
            if(  $this->status === 'Declinada' && $closed_reports ){
                return toastr()->error('La orden no puede ser establecida como declinada, no hay reportes diligenciados.','Error');
            }
        ServiceOrderHandle::cancel_status_report( $this->service_order_id );
        GeneralReportService::closed_general_report( $this->service_order_id  );

        if( $this->activity === 'Preventiva' || $this->activity === 'Mixta' ){
            ServicesSchedule::restart_status_schedule( $this->service_order_id  );
        }
        if( $this->activity === 'Correctiva' || $this->activity === 'Mixta'){
            ClientEquipmentCorrectiveService::restart_status_corrective( $this->service_order_id );
        }


          $this->service_order->status = $this->status;
          $this->service_order->observations_status = $this->observations_status;
          $this->service_order->rejected_by = $this->rejected_user_id;
          $this->service_order->save();
         EventServices::close_event_by_reject( $this->service_order );
          $this->dispatch('reload_service_orders');
          toastr()->success('Estado de la orden de servicios actualizada con éxito','Felicitaciones');

         $this->closeModal();
    }


    public function rules()
    {
        return [
            'observations_status' => [
                'required',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 10 ){
                        $fail('La observación debe contener la menos 10 caracteres');
                    }
                }
            ],
        ];
    }

    protected function messages()
    {
        return [
            'observations_status.required' => 'La observación es requerida.',

        ];
    }
}
