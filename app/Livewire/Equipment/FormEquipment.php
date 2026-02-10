<?php

namespace App\Livewire\Equipment;

use App\Models\Ampere;
use App\Models\Brand;
use App\Models\Equipment;
use App\Models\EquipmentClass;
use App\Models\EquipmentModel;
use App\Models\Volt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormEquipment extends Component
{
    #[Locked]
    public $id;

    public $name, $status, $equipment_class_id, $brand_id, $equipment_model_id, $volt_id, $ampere_id;
    public $equipment_class_lists, $volt_lists, $ampere_lists, $brands_lists, $models_list = [];
    public $description,$asset_assignment, $routine_assignment;


    public function mount(Equipment $equipment)
    {
        Log::info('TEST EQUIPOS - 1',['data'=> $equipment]);
        if( $equipment->id ){
            $this->fill(
                $equipment->only('id','description','name','status',
                      'equipment_class_id','brand_id','equipment_model','volt_id',
                      'ampere_id','equipment_model_id','asset_assignment',
                        'routine_assignment')
            );
            Log::info('TEST EQUIPOS - 2',['data'=> $equipment]);
            $this->models_list = $this->get_equipment_model();
        }
        $this->get_equipment_class();
        $this->get_volts();
        $this->get_amperes();
        $this->get_brands();

    }

    public function render()
    {
        return view('livewire.equipment.form');
    }

    public function updateOrStore()
    {
        $this->validate();

        $equipment_id = ['id'=>$this->id];
        $data = [
            'description'         => $this->description,
            'name'                => $this->name,
            'slug'                => Str::slug($this->name, '-'),
            'status'              => 1,
            'equipment_class_id'  => $this->equipment_class_id,
            'brand_id'  => $this->brand_id,
            'equipment_model_id'  => $this->equipment_model_id,
            'volt_id'  => $this->volt_id,
            'ampere_id'  => !$this->ampere_id ? null : $this->ampere_id,

        ];
        Equipment::updateOrCreate($equipment_id,$data);

        $message = !$this->id ? 'creado':'actualizado';
        toastr()->success('El equipo se han '. $message .' con éxito!', 'Felicitaciones');
        redirect()->route('admin.equipments');
    }

    public function updatedEquipmentClassId()
    {
        $this->models_list = $this->get_equipment_model();
    }

    protected function get_equipment_model()
    {
       return  EquipmentModel::select('id','model')
            ->where('equipment_class_id',$this->equipment_class_id)
            ->where('status',true)->get();
    }

    protected function get_equipment_class()
    {
        $this->equipment_class_lists = EquipmentClass::select('id','name')
            ->where('status',true)->get();
    }


    protected function get_volts()
    {
        $this->volt_lists  = Volt::select('id','volt_measurement', 'unit')
            ->where('status',true)->get();
    }


    protected function get_amperes()
    {
        $this->ampere_lists = Ampere::select('id','amperage_measurement', 'unit')
            ->where('status',true)->get();
    }

    protected function get_brands()
    {
        $this->brands_lists  = Brand::select('id','name')
            ->where('status',true)->get();
    }




    public function rules()
    {
        return [
           'name' => 'required',
           'equipment_model_id' => 'required|exists:equipment_models,id',
           'volt_id' => 'required|exists:volts,id',
           'brand_id' => 'required|exists:brands,id',
            'description' => [
                'nullable',
                'string',
                function( string $attribute, mixed $value, \Closure $fail ){
                    $value = trim( $value );
                    if( strlen( $value ) < 10 ){
                        $fail('La description debe contener la menos 10 caracteres');
                    }
                    if( strlen( $value ) > 1000 ){
                        $fail('La descripción debe contener un mnáximo de 1000 caracteres.');
                    }
                }
            ],
           'equipment_class_id'=> [
               'required',
               'exists:equipment_classes,id',
               Rule::unique('equipments')->where( function ($query) {
                   return $query->where('equipment_model_id',$this->equipment_model_id)
                       ->where('equipment_class_id', $this->equipment_class_id)
                       ->where('brand_id',$this->brand_id)
                       ->where('volt_id',$this->volt_id)
                       ->where('ampere_id',$this->ampere_id)
                       ->where('name',$this->name);
               })->ignore($this->id,'id'),
           ],

        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'El nombre ya  está en uso.',
            'equipment_model_id.required' => 'El modelo es requerido.',
            'equipment_class_id.unique' => 'Un equipo con estas características se ha creado previamente.',
            'equipment_model_id.exists' => 'El modelo no existe en los registros.',
            'equipment_class_id.required' => 'La calse de equipo es requerida.',
            'volt_id.required' => 'Los voltios son requeridos.',
            'brand_id.required' => 'La marca es requerida.',
            'equipment_class_id.exists' => 'La clase de equipo no existe en los registros.',
            'volt_id.exists' => 'Este medida de voltio no existe en los registros.',
            'brand_id.exists' => 'La marca no existe en los registros.',
        ];

    }
}
