<?php

namespace App\Livewire\PreventiveRoutine;

use App\Models\Equipment;
use App\Models\EquipmentClass;
use App\Models\PreventiveActivity;
use App\Models\PreventiveRoutine;
use App\Models\PreventiveRoutineActivity;
use App\Models\PreventiveRoutineEquipment;
use App\Models\Schedule;
use App\Services\Equipment\DataEquipment;
use App\Services\PreventiveRoutine\ServicePreventiveRoutine;
use App\Services\Schedule\ServicesSchedule;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPreventiveRoutine  extends Component
{

    public PreventiveRoutine $preventive_routine;
     public $equipment_classes_list = [], $equipment_class_id, $equipments_list = [], $activities_list = [];

     public $name, $frequency, $equipments_check_inputs = [], $activities_check_inputs = [];
     public $action, $routine_validator;

    #[Locked]
    public $id;

    public function mount(PreventiveRoutine $preventive_routine  )
    {
        $this->equipment_classes_list  = EquipmentClass::getEquipmentClasses()->get();

        $this->fill(
            $preventive_routine->only('id','name','frequency','equipment_class_id')
        );

        $this->activities_list = PreventiveActivity::getActivitiesByClass( $this->equipment_class_id )
            ->get()->toArray();
        if( $this->id ) $this->equipments_list = DataEquipment::get_equipment_by_routine( $this->equipment_class_id, $this->id );

        if($this->id){
            $this->get_stored_equipments();
            $this->get_stored_activities();
            $this->get_schedule_routine();
            $this->action = 1;
        }else{
            $this->action = 0;
        }


    }


    public function render()
    {
        return view('livewire.preventiveRoutine.form');
    }


    public function updateOrStore()
    {
        //Start validation
        $this->validate();
        $check_validator = $this->validate_check();
        if($check_validator) return  toastr()->error($check_validator, 'Error!');

        $routine_validator = $this->routine_validator();
        if( $routine_validator ){
            return toastr()->error( 'El equipo o la actividad seleccionada ya está en uso por: '. $routine_validator .'. Elija otro equipo o actividad.', 'Error!');
        }

        if($this->id){
            $original_values = $this->preventive_routine->toArray();
            if( $original_values['frequency'] != intval($this->frequency) ){
                return $this->dispatch('open_modal');

            }
        }
        $this->equipments_check_inputs  = array_unique( $this->equipments_check_inputs );
        $this->activities_check_inputs = array_unique( $this->activities_check_inputs  );
        $this->execute_store_update();


    }



    protected function execute_store_update()
    {
        $find_id = ['id'=>$this->id];

        $data_routine = [
            'name'    => $this->name,
            'status'   => 1,
            'frequency'=> $this->frequency,
            'equipment_class_id'=> $this->equipment_class_id,
        ];

        $preventive_routine = PreventiveRoutine::updateOrCreate($find_id,$data_routine);

        if($this->id) {

            $this->update_activities();
            $this->unassign_equipment();
            $this->update_equipments();
        }else{

            $this->store_equipments( $preventive_routine->id );
            $this->store_activities( $preventive_routine->id );
            $this->assign_equipment();
        }

        $message = !$this->id ? 'creado':'actualizado';
        toastr()->success('La rutina se ha '. $message .' con éxito!', 'Felicitaciones');
        return  redirect()->route( 'admin.preventive-routine' );
    }


    #[On('update_routine')]
    public function update_routine()
    {
        $this->execute_store_update();
        $frequency = intval($this->frequency);
        ServicesSchedule::update_schedule_by_frequency($this->id, $frequency );
    }


    public function updatedEquipmentClassId( $property )
    {
        $equipment_class_id = $property;

        $this->equipments_check_inputs = [];
        $this->activities_check_inputs = [];
        $this->equipments_list = DataEquipment::get_equipments( $equipment_class_id );
        $this->activities_list = PreventiveActivity::getActivitiesByClass( $equipment_class_id )
            ->get();
    }

    protected function validate_check()
    {
        $error_message = '';
        if(empty($this->equipments_check_inputs) && empty($this->activities_check_inputs) )  return 'Oops! Selecciona al menos un equipo y una actividad!';
        if(empty($this->activities_check_inputs)  ) return 'Oops! Selecciona al menos una actividad!';
        if(empty($this->equipments_check_inputs)  ) return 'Oops! Selecciona al menos un equipo!';
        return $error_message;

    }

    protected function store_equipments( $preventive_routine_id )
    {
        $equipments = $this->equipments_check_inputs;
        foreach ($equipments as $index => $equipment_id)
        {
            PreventiveRoutineEquipment::create([
                'equipment_id'=> $equipment_id,
                'preventive_routine_id'=> $preventive_routine_id
            ]);
        }
    }


    protected function assign_equipment()
    {
        $equipments = $this->equipments_check_inputs;
        foreach ($equipments as $index => $equipment_id)
        {
          Equipment::where('id',$equipment_id)
              ->update([
                  'routine_assignment' => 1
              ]);
        }
    }


    protected function unassign_equipment()
    {
         $equipments =  PreventiveRoutineEquipment::where('preventive_routine_id',$this->id)
                                    ->select('equipment_id')->get();

         foreach ( $equipments as $equipment ){
             Equipment::where('id', $equipment->equipment_id)
                 ->update([
                     'routine_assignment' => 0
                 ]);
         }

         $this->assign_equipment();

    }


    protected function store_activities( $preventive_routine_id )
    {
        $activities = $this->activities_check_inputs;
        foreach ($activities as $index => $activity_id)
        {
            PreventiveRoutineActivity::create([
                'preventive_activity_id'=> $activity_id,
                'preventive_routine_id'=> $preventive_routine_id
            ]);
        }

    }


    protected function update_equipments()
    {
        PreventiveRoutineEquipment::where('preventive_routine_id',$this->id)->delete();
        $this->store_equipments($this->id);
    }

    protected function update_activities()
    {
        PreventiveRoutineActivity::where('preventive_routine_id',$this->id)->delete();
        $this->store_activities($this->id);
    }


    protected function get_stored_equipments()
    {
        $stored_equipments =  PreventiveRoutineEquipment::select('equipment_id')
            ->where('preventive_routine_id',$this->id)->get();

        foreach ($stored_equipments as $equipment){
            $this->equipments_check_inputs[] = $equipment->equipment_id;
        }
    }

    protected function get_stored_activities()
    {
        $stored_activities =  PreventiveRoutineActivity::select('preventive_activity_id')
            ->where('preventive_routine_id',$this->id)->get();

        foreach ($stored_activities as $activity){
            $this->activities_check_inputs[] = $activity->preventive_activity_id;
        }
    }

    protected function get_schedule_routine()
    {
        $this->routine_validator = Schedule::join('preventive_routines','schedules.preventive_routine_id','preventive_routines.id')
                                      ->where('preventive_routines.id',$this->id)
                                      ->select('schedules.id')->get()->toArray();

        if( count( $this->routine_validator ) === 0 ){
            $this->routine_validator = false;
        }else{
            $this->routine_validator = true;
        }
    }


    protected function routine_validator()
    {
        if(PreventiveRoutine::all()->count() === 0) return false;


        $validator_data = [
            'equipments'=>$this->equipments_check_inputs,
            'activities'=>$this->activities_check_inputs,
            'routine_id'=>$this->id,
        ];
        return ServicePreventiveRoutine::createOrUpdated( $validator_data );

    }


    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('preventive_routines')->ignore($this->id)
            ],
            'frequency'=> 'required'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'frequency.required' => 'La rutina es requerida.',
            'name.unique' => 'El nombre ya está en uso.',
        ];
    }
}
