<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use LivewireUI\Modal\ModalComponent;

class HandleState  extends ModalComponent
{

    public  ServiceOrder $service_order;

    public $serial,$status;


    public function mount( ServiceOrder $service_order )
    {
        $this->serial        = $service_order->serial;
        $this->service_order = $service_order;
        $this->status        = $service_order->status;
    }

    public function render()
    {
        return view('livewire.serviceOrder.handleState');
    }


    public function handleStatus()
    {
        $this->validate();
        $this->service_order->status = $this->status;
        $this->service_order->save();
        $this->dispatch('reload_service_orders');
        $this->closeModal();
        return toastr()->success('Estado de la orden actualizado exitosamente','Felicitaciones!');
    }


    public function rules()
    {
        return [
            'status' => 'required|in:Facturada,Cerrada'
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Este campo es requerido',
            'status.in' => 'Seleccion un estado valido',
        ];
    }


}
