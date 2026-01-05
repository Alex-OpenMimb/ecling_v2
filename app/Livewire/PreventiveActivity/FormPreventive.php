<?php

namespace App\Livewire\PreventiveActivity;

use App\Models\EquipmentClass;
use App\Models\PreventiveActivity;
use App\Models\PreventiveRoutineActivity;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class FormPreventive extends  ModalComponent
{


    public $equipment_classes_list, $equipment_class_id, $activity, $description, $assignment;

    #[Locked]
    public $id;

    public function render()
    {
        return view('livewire.preventiveActivity.form');

    }

    public function mount(  PreventiveActivity $preventive_activity )
    {
        $this->fill(
            $preventive_activity->only( 'id','activity','description','equipment_class_id')
        );

        $this->equipment_classes_list =  EquipmentClass::getEquipmentClasses()->get();

        if( $this->id ){
            $this->assignment = $this->validate_state_activity();
        }
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

        PreventiveActivity::updateOrCreate( $find_id, $data );
        $this->dispatch('reload_activity_preventive');
        toastr()->success('La actividad se ha '. $message .' con éxito!', 'Felicitaciones');
        $this->closeModal();
    }


    protected function validate_state_activity()
    {
        return PreventiveRoutineActivity::where('preventive_activity_id',$this->id)
            ->count();
    }

    protected function rules()
    {
        return [
            'activity'=> [
                'required',
                Rule::unique('preventive_activities')->where( function ($query) {
                    return $query->where('activity',$this->activity);
                })->ignore($this->id,'id')
            ],
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
            'equipment_class_id'=> 'required|exists:equipment_classes,id'

        ];
    }




}
