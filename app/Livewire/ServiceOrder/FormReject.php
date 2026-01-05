<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class FormReject extends ModalComponent
{

    public ServiceOrder $service_order;
    public $observations_status, $serial, $rejected_user_id,$rejected_by,$rejected_name;


    public function mount( ServiceOrder $service_order )
    {
        $this->rejected_user_id = auth()->user()->id;
        $this->service_order = $service_order;
        $this->fill(
            $service_order->only('observations_status','serial','rejected_by')
        );

        $this->rejected_name = User::find( $this->rejected_by )->name;

    }



    public function render()
    {
        return view('livewire.serviceOrder.formReject');
    }
}
