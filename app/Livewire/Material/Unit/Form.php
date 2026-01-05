<?php

namespace App\Livewire\Material\Unit;

use App\Models\Unit;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class Form  extends   ModalComponent
{
    public Unit $unit;
    public $unit_name;
    #[Locked]
    public $id;

    public function mount(Unit $unit = null  )
    {
        if( $unit )
        {
            $this->fill(
                $unit->only(
                    'id', 'unit_name'
                )
            );
        }
    }

    public function render()
    {
        return view( 'livewire.material.unit.form');
    }

    public function updateOrStore()
    {
        $this->validate();
        $find_id =['id'=>$this->id];
        $data = [
            'unit_name'=>$this->unit_name,
            'status'=> 1

        ];
        $message = $this->id ? 'actualizado ':'creado';
        Unit::updateOrCreate($find_id,$data);

        $this->dispatch('reload_unit');
        toastr()->success('La unidad se ha'. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }

    protected function rules()
    {
        return [
            'unit_name'=> [
                'required',
                Rule::unique('units')->where( function ($query) {
                    return $query->where('unit_name',$this->unit_name);
                })->ignore($this->id,'id')
            ]
        ];
    }


    protected function messages()
    {
        return [
            'unit_name.required' => 'El nombre es requerido.',
            'unit_name.unique' => 'El nombre del material ya está en uso.',
        ];
    }
}
