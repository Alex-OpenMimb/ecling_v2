<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use LivewireUI\Modal\ModalComponent;

class Observations  extends ModalComponent
{
    public ServiceOrder $order;
    public $observations, $serial;
    public function mount( ServiceOrder $order )
    {
        $this->order = $order;
        $this->fill(
            $order->only('observations','serial')
        );
    }


    public function render()
    {
        return view('livewire.serviceOrder.observations');
    }


    public function updateOrStore()
    {
        $this->validate();
        $this->order->observations = $this->observations;
        $this->order->save();
        if( !preg_match('/^\s*$/', $this->observations) ) toastr()->success('Observación agregada con éxito!','Felicitaciones');
        $this->dispatch('reload_service_orders');
        $this->closeModal();
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
                }
            ],
        ];
    }

    protected function messages()
    {
        return [
            'observations.required' => 'La observación no es valida.',

        ];
    }

}
