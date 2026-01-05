<?php

namespace App\Livewire\CorrectiveActivity;

use App\Models\CorrectiveActivity;
use App\Models\EquipmentClass;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormCorrective   extends ModalComponent
{

    public $equipment_classes_list,$activity,$description,$equipment_class_id,$assigned;

    #[Locked]
    public $id;


    public function mount( CorrectiveActivity $corrective_activity )
    {
        if ($corrective_activity->id){
            $this->fill(
                $corrective_activity->only('id','activity',
                    'description',
                    'equipment_class_id',
                  'assigned')
            );
        }
        $this->equipment_classes_list =  EquipmentClass::getEquipmentClasses()->get();
    }


    public function render()
    {
        return view('livewire.correctiveActivity.form');
    }


    public function updateOrStore()
    {
        $this->validate();
        $find_id =['id'=>$this->id];
        $data = [
            'activity' => $this->activity,
            'description' => $this->description,
            'equipment_class_id' => $this->equipment_class_id,
        ];
        $message = $this->id ? 'actualizado ':'creado';
        CorrectiveActivity::updateOrCreate($find_id,$data);
        $this->dispatch('reload_corrective_activity');
        toastr()->success('La actividad se ha '. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }


    protected function rules()
    {
        return [
            'activity'=> [
                'required',
                Rule::unique('corrective_activities')->where( function ($query) {
                    return $query->where('activity',$this->activity);
                })->ignore($this->id,'id')
            ],
            'equipment_class_id'=>'required|exists:equipment_classes,id',
            'description' => [
                'nullable',
                'string',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 10 ){
                        $fail('La descripción debe contener la menos 10 caracteres.');
                    }
                    if( strlen( $value ) > 300 ){
                        $fail('La descripción debe contener un mnáximo de 300 caracteres.');
                    }
                }
            ],


        ];
    }


    protected function messages()
    {
        return [
            'activity.required' => 'La actividad es requerida.',
            'equipment_class_id.required' => 'La clase de equipo es requerido.',
            'equipment_class_id.exists' => 'La clase de equipo seleccionada no existe.',
            'activity.unique' => 'La actividad esta ya está en uso.',
        ];
    }


}
