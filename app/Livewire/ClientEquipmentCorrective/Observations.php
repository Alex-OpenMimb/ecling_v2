<?php

namespace App\Livewire\ClientEquipmentCorrective;

use App\Models\ClientsEquipmentsCorrective;
use LivewireUI\Modal\ModalComponent;

class Observations extends ModalComponent
{

    public $observations, $corrective;

    public function mount( ClientsEquipmentsCorrective $corrective )
    {
        $this->corrective = $corrective;
        $this->fill(
            $corrective->only('observations')
        );

    }

    public function render()
    {
        return view('livewire.clientEquipmentCorrective.observations');
    }

    public function updateOrStore()
    {
        $this->validate();
        $this->corrective->observations = $this->observations;
        $this->corrective->save();
        if( !preg_match('/^\s*$/', $this->observations) ) toastr()->success('Observación agregada con éxito!','Felicitaciones');

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
