<?php

namespace App\Livewire\Headquarter;



use App\Models\Address;
use App\Models\City;
use App\Models\Client;
use App\Models\Department;
use App\Models\Headquarter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormHeadquarter extends Component
{

    public Headquarter $headquarter;

    #[LOCKED]
    public $client_id;
    #[LOCKED]
    public $headquarter_id,$client_slug;

    public $city_id;
    public $department_id;
    #[LOCKED]
    public $address_id;

    public $action= 1,  $name_client, $headquarter_name, $departments, $cities_list= [];

    public $name, $phone_1, $phone_2, $contact_name,$status,$email;

    public  $nomenclature_main,$number_main, $nomenclature_second,$number_second, $number,  $city_name, $observations;
    public  $departments_name;

    public function mount(Client $client, Headquarter $headquarter )
    {
        $this->status         = $client->status;
        $this->client_id      =  $client->id;
        $this->client_slug    =  $client->slug;
        $this->headquarter    =  $headquarter;
        $this->headquarter_id =  $headquarter->id;

        if($this->headquarter_id){
            $this->fill(
                $headquarter->only('name','contact_name','phone_1','phone_2','address_id','email')
            );
            $this->get_location();
            $this->cities_list = City::getCities( $this->department_id )->get();

        }

        $this->action = $this->headquarter_id ? 0 : 1;
        $this->name_client    =  $client->name;
        if($headquarter->id) $this->headquarter_name = $this->headquarter->name;
        $this->departments = Department::getDepartments()->get();
    }


    public function render()
    {
        $client_id = $this->client_id;

        return view('livewire.headquarter.form',['client_id'=>$client_id]);
    }

    public function updatedDepartmentId()
    {
        $this->city_id = '';
        $this->cities_list = City::getCities( $this->department_id )->get();

    }

    public function updateOrStore( $action )
    {
        if( !$this->status ) return  toastr()->error('No es posible actualizar la sucursal porque el cliente está desactivado.', 'Error');
        $this->validate();
        $find_address_id = ['id'=>$this->address_id];
        $address_data = [
            'nomenclature_main'  => !$this->nomenclature_main ? null: $this->nomenclature_main,
            'observations'       => !$this->observations ? null: $this->observations,
            'number_main'        => !$this->number_main ? null: $this->number_main,
            'nomenclature_second'=> !$this->nomenclature_second ? null : $this->nomenclature_second,
            'number_second'      => !$this->number_second ? null : $this->number_second,
            'number'             => !$this->number ? null: $this->number,
            'city_id'            => !$this->city_id ? null : $this->city_id
        ];


        $address =  Address::updateOrCreate($find_address_id, $address_data);

        //If is updating keep the same value to main.
        if($this->headquarter_id)  $main = $this->headquarter->main;
        else{
            $main = false;
        }

        $find_headquarter_id = ['id'=> $this->headquarter_id ];
        $headquarter_data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name,'-'),
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone_1' => $this->phone_1,
            'phone_2' => $this->phone_2,
            'client_id' => $this->client_id,
            'address_id' => $address->id,
            'main' => $main,
        ];

        Headquarter::updateOrCreate($find_headquarter_id, $headquarter_data);

        $message = $action ? 'creado':'actualizado';
        toastr()->success('Los datos se han '. $message .' con éxito!', 'Felicitaciones');
        return redirect()->route('admin.headquarters',['client'=>$this->client_slug ]);
    }

    protected function get_location()
    {
        $address = Headquarter::select( 'addresses.id as address_id', 'addresses.nomenclature_main','addresses.number_main',
            'addresses.nomenclature_second','addresses.number_second',
            'addresses.number','cities.id as city_id','cities.name as city_name','departments.id as departments_id',
            'departments.name as departments_name','addresses.observations')
            ->join('addresses','headquarters.address_id','=','addresses.id')
            ->join('cities','addresses.city_id','=','cities.id')
            ->join('departments','cities.department_id','=','departments.id')
            ->where('headquarters.id',$this->headquarter_id)->first();

        $this->nomenclature_main   = $address->nomenclature_main;
        $this->number_main         = $address->number_main;
        $this->nomenclature_second = $address->nomenclature_second;
        $this->number_second       = $address->number_second;
        $this->number              = $address->number;
        $this->city_id             = $address->city_id;
        $this->city_name           = $address->city_name;
        $this->department_id       = $address->departments_id;
        $this->departments_name    = $address->departments_name;
        $this->address_id          = $address->address_id;
        $this->observations          = $address->observations;


    }



    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:4',
                Rule::unique('headquarters')->ignore($this->headquarter),
            ],
            'phone_1'=>'required|min:7|max:10',
            'phone_2'=>'nullable|min:7|max:10',
            'contact_name'=>'required|min:4',
            'city_id'=>'required|exists:cities,id',
//            'nomenclature_main'=> [
//                Rule::unique('addresses')->where( function ($query) {
//                    return $query->where('nomenclature_main',$this->nomenclature_main)
//                        ->where('number_main', $this->number_main)
//                        ->where('number',$this->number)
//                        ->where('city_id',$this->city_id);
//                })->ignore( $this->address_id,'id'),
//            ]

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'contact_name.required' => 'El contacto es requerido.',
            'phone_1.required' => 'El teléfono es requerido.',
            'city_id.required' => 'La ciudad es requerida.',
            'city_id.exists' => 'La ciudad no esta registrada.',
            'phone_2.min' => 'El teléfono debe contener al menos 7 números.',
            'phone_2.max' => 'El teléfono debe contener máximo 10 números.',
            'phone_1.min' => 'El teléfono debe contener al menos 7 númeross.',
            'phone_1.max' => 'El teléfono debe contener máximo 10 números.',
            'name.min' => 'El nombre debe tener al menos  4 caracteres.',
            'contact_name.min' => 'El contacto debe tener al menos  4 caracteres.',
            'nomenclature_main.unique' => 'La dirección proporcionado ya está en uso.',
            'email.unique' => 'La dirección proporcionado ya está en uso.',
            'email.required' => 'El email es requerido.',

        ];

    }
}
