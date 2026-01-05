<?php

namespace App\Livewire\Store\Location;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormLocation  extends ModalComponent
{
    public $name,$status;

    #[Locked]
    public $id;


    public function mount(Location  $location )
    {
        $this->fill(
            $location->only('id','name','status')
        );

    }
    public function render()
    {
        return view('livewire.store.location.form');
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
        Location::updateOrCreate($band_id,$data);
        $this->dispatch('reload_location');
        toastr()->success('La ubicación se ha '. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    protected function rules()
    {
        return [
            'name'=> [
                'required',
                Rule::unique('locations')->where( function ($query) {
                    return $query->where('name',$this->name);
                })->ignore($this->id,'id')
            ]
        ];
    }


    protected function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.unique' => 'El nombre esta ya está en uso.',
        ];
    }
}
