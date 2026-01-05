<?php

namespace App\Livewire\Store\Model;

use App\Models\EquipmentClass;
use App\Models\EquipmentModel;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;
class FormModel extends ModalComponent
{
    public $model, $equipment_class_list, $equipment_class_id;
    #[LOCKED]
    public $id;

    public function mount(EquipmentModel $model = null)
    {
        if($model){
        $this->fill(
            $model->only('id','model','equipment_class_id')
        );
        }

        $this->get_equipment_class();

    }

    public function updateOrStore()
    {
        $this->validate();
        $find_id = ['id' => $this->id];
        $data = [
            'model' => $this->model,
            'slug' => Str::slug($this->model, '-'),
            'equipment_class_id'=> $this->equipment_class_id
        ];
        $message = $this->id ? 'actualizado ' : 'creado';
        EquipmentModel::updateOrCreate($find_id, $data);
        $this->dispatch('reload_models');
        toastr()->success('El modelo se ha' . $message . ' con éxito!', 'Felicitaciones');
        $this->closeModal();

    }



    public function render()
    {
        return view('livewire.store.model.form');

    }


    protected function rules()
    {
        return [
            'model'=> [
                'required',
                Rule::unique('equipment_models')->where( function ($query) {
                    return $query->where('model',$this->model);
                })->ignore($this->id,'id')
            ],
            'equipment_class_id'=>'required|exists:equipment_classes,id'
        ];
    }


    protected function get_equipment_class()
    {
        $this->equipment_class_list = EquipmentClass::getEquipmentClasses()->get();
    }


    protected function messages()
    {
        return [
            'model.required' => 'El modelo es requerido.',
            'equipment_class_id.required' => 'La clase de equipo es requerido.',
            'equipment_class_id.exists' => 'La clase de equipo no existe en los registros.',
            'model.unique' => 'El modelo ya está en uso.',
        ];
    }


    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
