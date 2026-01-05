<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class Observations extends ModalComponent
{

    public $schedule, $observations;

    #[Locked]
    public $id;

    public function render()
    {
        return view('livewire.schedule.observations');
    }


    public function mount( Schedule $schedule )
    {
        $this->schedule = $schedule;
        $this->fill(
            $schedule->only('observations')
        );

    }


    public function updateOrStore()
    {
        $this->validate();
        $this->schedule->observations = $this->observations;
        $this->schedule->save();
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
