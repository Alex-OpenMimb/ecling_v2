<?php

namespace App\Livewire\Store\Ampere;

use App\Models\Ampere;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormAmpere extends ModalComponent
{

   public  Ampere $ampere;
    public $amperage_measurement, $unit;

    #[Locked]
    public $id;


    public function mount(Ampere $ampere = null )
    {
        if( $ampere ){
            $this->amperage_measurement = $ampere->amperage_measurement;
            $this->unit = $ampere->unit;
            $this->id = $ampere->id;
        }
    }

    public function render(  )
    {
        return view('livewire.store.ampere.form');
    }



    public function updateOrStore()
    {
        $this->validate();
        $find_id = ['id' => $this->id];
        $data = [
            'amperage_measurement' => $this->amperage_measurement,
            'unit'=> $this->unit
        ];
        $message = $this->id ? 'actualizado ' : 'creado';
        Ampere::updateOrCreate($find_id, $data);
        $this->dispatch('reload_ampere');
        toastr()->success('La medida de amperio se ha ' . $message . ' con éxito!', 'Felicitaciones');
        $this->closeModal();

    }


    protected function rules()
    {
        return [
            'amperage_measurement'=> [
                'required',
                Rule::unique('amperes')->where( function ($query) {
                    return $query->where('amperage_measurement',$this->amperage_measurement);
                })->ignore($this->id,'id')
            ],
            'unit'=>'required'
        ];
    }


    protected function messages()
    {
        return [
            'amperage_measurement.required' => 'La medida es requerido.',
            'unit.required' => 'La unidad es requerido.',
            'amperage_measurement.unique' => 'La medida ya está en uso.',
        ];
    }


    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
