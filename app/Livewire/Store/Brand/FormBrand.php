<?php

namespace App\Livewire\Store\Brand;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormBrand extends ModalComponent
{
    public $action;
    public $name;
    #[LOCKED]
    public $id;

    public function mount( Brand $brand = null)
    {
        if($brand){
            $this->name= $brand->name;
            $this->id = $brand->id;
        }

    }

    public function updateOrStore()
    {
        $this->validate();
        $band_id =['id'=>$this->id];
        $data = [
            'name'=>$this->name,
            'slug'=>Str::slug($this->name,'-'),
        ];
        $message = $this->id ? 'actualizado ':'creado';
        Brand::updateOrCreate($band_id,$data);
        $this->dispatch('reload_brand');
        toastr()->success('La marca se ha'. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.store.brand.form');
    }


    protected function rules()
    {
        return [
            'name'=> [
                'required',
                Rule::unique('brands')->where( function ($query) {
                    return $query->where('name',$this->name);
                })->ignore($this->id,'id')
            ]
        ];
    }


    protected function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.unique' => 'El nombre  ya está en uso.',
        ];
    }


    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
