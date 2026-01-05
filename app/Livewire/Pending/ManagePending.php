<?php

namespace App\Livewire\Pending;

use App\Models\PendingActivity;
use Faker\Provider\en_UG\PhoneNumber;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;

class ManagePending  extends ModalComponent
{

    public  PendingActivity $pendingActivity;
    public $management_observations, $status;

    #[Locked]
    public $id;



    public function mount (PendingActivity $pendingActivity  )
    {
        $this->pendingActivity = $pendingActivity;
        $this->status = $pendingActivity->status;
        $this->management_observations = $this->pendingActivity->management_observations;
    }

    public function render()
    {
        return view('livewire.pending.manage');
    }



    public function managePending()
    {
        $this->validate();
        $this->pendingActivity->management_observations = trim($this->management_observations);
        $this->pendingActivity->status = $this->status;
        $this->pendingActivity->save();
        $this->dispatch('reload_pending_activity');
        $this->closeModal();
        return toastr()->success('Estado gestionado con éxito','Felicidades!');
    }

    public function rules()
    {
        return [
            'status' => 'required|in:Abierto,Cerrado,Rechazado',
            'management_observations' => [
                'required',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 5 ){
                        $fail('La observación debe contener la menos 10 caracteres.');
                    }
                }

            ]
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Este campo es requerido.',
            'status.in' => 'Seleccion un estado valido.',
            'management_observations.required' => 'Este campo es requerido.'
        ];
    }


}
