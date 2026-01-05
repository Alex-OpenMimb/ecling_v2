<?php

namespace App\Livewire\Material\SparePart;

use App\Models\SparePart;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class Form    extends ModalComponent
{
    public SparePart $sparePart;

    public $spare_part_name;

    #[Locked]
    public $id;

    public function mount(SparePart $sparePart = null )
    {
        if( $sparePart )
        {
            $this->fill(
                $sparePart->only(
                    'id', 'spare_part_name'
                )
            );
        }
    }

    public function render()
    {
        return view( 'livewire.material.sparePart.form');
    }

    public function updateOrStore()
    {
        $this->validate();
        $find_id =['id'=>$this->id];
        $data = [
            'spare_part_name'=>$this->spare_part_name,
            'status'=> 1

        ];
        $message = $this->id ? 'actualizado ':'creado';
        SparePart::updateOrCreate($find_id,$data);

        $this->dispatch('reload_spare_part');
        toastr()->success('El repuesto se ha'. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }


    protected function rules()
    {
        return [
            'spare_part_name'=> [
                'required',
                Rule::unique('spare_parts')->where( function ($query) {
                    return $query->where('spare_part_name',$this->spare_part_name);
                })->ignore($this->id,'id')
            ]
        ];
    }


    protected function messages()
    {
        return [
            'spare_part_name.required' => 'El nombre es requerido.',
            'spare_part_name.unique' => 'El nombre del material ya está en uso.',
        ];
    }
}
