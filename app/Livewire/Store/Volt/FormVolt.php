<?php

namespace App\Livewire\Store\Volt;

use App\Models\Volt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormVolt extends  ModalComponent
{

    public Volt $volt;
    public $volt_measurement, $unit;

    #[Locked]
    public $id;

    public function mount( Volt $volt = null )
    {
        if($volt){
            $this->volt_measurement  = $volt->volt_measurement;
            $this->unit = $volt->unit;
            $this->id = $volt->id;

        }
    }
    public function render(  )
    {
        return view('livewire.store.volt.form');

    }


    public function updateOrStore()
    {
        $this->validate();
        $find_id = ['id' => $this->id];
        $data = [
            'volt_measurement' => $this->volt_measurement,
             'unit'=> $this->unit
        ];
        $message = $this->id ? 'actualizado ' : 'creado';
        Volt::updateOrCreate($find_id, $data);
        $this->dispatch('reload_volts');
        toastr()->success('La medida de voltaje se ha ' . $message . ' con éxito!', 'Felicitaciones');
        $this->closeModal();

    }


    protected function rules()
    {
        return [
            'volt_measurement'=> [
                'required',
                Rule::unique('volts')->where( function ($query) {
                    return $query->where('volt_measurement',$this->volt_measurement);
                })->ignore($this->id,'id')
            ],
            'unit'=>'required'
        ];
    }


    protected function messages()
    {
        return [
            'volt_measurement.required' => 'La medida es requerido.',
            'unit.required' => 'La unidad es requerido.',
            'volt_measurement.unique' => 'La medida ya está en uso.',
        ];
    }


    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
