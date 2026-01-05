<?php

namespace App\Livewire\Material\Material;

use App\Models\Material;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class Form   extends  ModalComponent
{
    public Material $material;

    public $material_name;

    #[Locked]
    public $id;

    public function mount(Material $material = null  )
    {
        if( $material ){
            $this->fill(
                $material->only('id','material_name')
            );
        }

    }


    public function updateOrStore()
    {
        $this->validate();
        $find_id =['id'=>$this->id];
        $data = [
            'material_name'=>$this->material_name,
            'status'=> 1

        ];
        $message = $this->id ? 'actualizado ':'creado';
        Material::updateOrCreate($find_id,$data);

        $this->dispatch('reload_material');
        toastr()->success('El material se ha'. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }

    public function render()
    {
        return view( 'livewire.material.materials.form');
    }




    protected function rules()
    {
        return [
            'material_name'=> [
                'required',
                Rule::unique('materials')->where( function ($query) {
                    return $query->where('material_name',$this->material_name);
                })->ignore($this->id,'id')
            ]
        ];
    }


    protected function messages()
    {
        return [
            'material_name.required' => 'El nombre es requerido.',
            'material_name.unique' => 'El nombre del material ya está en uso.',
        ];
    }

}
