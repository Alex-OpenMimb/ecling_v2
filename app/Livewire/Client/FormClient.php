<?php

namespace App\Livewire\Client;

use App\Models\Address;
use App\Models\City;
use App\Models\Client;
use App\Models\Department;
use App\Models\Headquarter;
use App\Rules\UniqueNameHeadquarter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormClient extends Component
{
    public Client $client;
    public $action,$departments, $departmentId,$cityId,$cities_list = [];
    public $name,$email,$nit,$phone_1,$phone_2,$contact_name,$nomenclature_main,$number_main;
    public $nomenclature_second,$number_second ,$number,$observations, $status, $headquarter_name;
    #[LOCKED]
    public $id;
    #[LOCKED]
    public $headquarters_id;
    #[LOCKED]
    public $address_id;

    public function mount(Client $client)
    {

        $this->client = $client;

        $this->fill(
            $client->only('id','name','nit','status')
        );

        $this->action     = $client->id ? 0:1;  //create = 1, edit = 0
        //Edit a client to set the address
        if( !$this->action )  {
            $this->get_location();
            $this->cities_list = City::getCities( $this->departmentId )->get();
            $headquarter = Headquarter::where('main',true)
                ->where('client_id',$this->id)->select('email','name')->first();
            $this->email = $headquarter->email;
            $this->headquarter_name = $headquarter->name;

        }
        $this->departments = Department::getDepartments()->get();

    }

    public function render()
    {
        return view('livewire.client.form');

    }


    public function updatedDepartmentId()
    {
        $this->cityId  = '';
        $this->cities_list = City::getCities( $this->departmentId )->get();

    }

    public function updateOrStore($actionType)
    {
        if($this->id){
            if( !$this->client->status ) return  toastr()->error('No es posible actualizar el cliente porque está desactivado.', 'Error');
        }
        $this->validate();
        $find = ['id'=>$this->id];
        $client_data = [
            'name'=> $this->name,
            'slug'=> Str::slug($this->name, '-'),
            'nit'=> $this->nit,
        ];

        $address_id = ['id'=>$this->address_id];
        $address_data =[
            'nomenclature_main'=> $this->nomenclature_main,
            'number_main'=> $this->number_main,
            'nomenclature_second'=> !$this->nomenclature_second ? null : $this->nomenclature_second,
            'number_second'=> !$this->number_second ? null : $this->number_second,
            'number' =>$this->number,
            'city_id'=> $this->cityId,
            'observations'=> $this->observations
        ];

        Cache::flush();
        $client = Client::updateOrCreate($find,$client_data);
        $address = Address::updateOrCreate($address_id,$address_data);


        $headquarters_id = [ 'id'=> $this->headquarters_id ];
        $headquarters_data = [
            'name'        => $this->headquarter_name,
            'slug'        => Str::slug($this->name, '-'),
            'main'        => 1,
            'phone_1'     => $this->phone_1,
            'phone_2'     => $this->phone_2,
            'contact_name'=> $this->contact_name,
            'email'       => $this->email,
            'client_id'   => $client->id,
            'address_id'  => $address->id,
        ];

        Headquarter::updateOrCreate( $headquarters_id, $headquarters_data  );
        $message = $actionType ? 'creado':'actualizado';

        toastr()->success('Los datos se han '. $message .' con éxito!', 'Felicitaciones');
        return redirect()->route('admin.clients');
    }

    protected function get_location()
    {
        $address = Headquarter::select('headquarters.contact_name','headquarters.phone_1','headquarters.phone_2','headquarters.id as headquarters_id',
            'addresses.nomenclature_main as main','addresses.number_main',
            'addresses.nomenclature_second as second', 'addresses.number_second',
            'addresses.number','addresses.id as address_id','cities.id as city_id',
            'departments.id as department_id','addresses.observations')
            ->join('clients','headquarters.client_id','=','clients.id')
            ->join('addresses','headquarters.address_id','=','addresses.id')
            ->join('cities','addresses.city_id','=','cities.id')
            ->join('departments','cities.department_id','=','departments.id')
            ->where('clients.id',$this->id)
            ->where('headquarters.main',true)->first();
        if( $address ){
            $this->nomenclature_main   = $address->main;
            $this->number_main         = $address->number_main;
            $this->nomenclature_second = $address->second;
            $this->number_second       = $address->number_second;
            $this->number              = $address->number;
            $this->cityId              = $address->city_id;
            $this->departmentId        = $address->department_id;
            $this->address_id          = $address->address_id;
            $this->headquarters_id     = $address->headquarters_id;
            $this->contact_name        = $address->contact_name;
            $this->phone_1             = $address->phone_1;
            $this->phone_2             = $address->phone_2;
            $this->observations        = $address->observations;
        }

    }


    public function rules()
    {

        return [
            'name' => [
                'required',
                'min:4',
                Rule::unique('clients')->ignore($this->client),
            ],
            'headquarter_name' => [
                'required',
                'min:4',
                  new UniqueNameHeadquarter( $this->headquarters_id ),
            ],
            'email' => [
                'required',
                'regex:/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('headquarters')->ignore($this->headquarters_id),
            ],
            'nit'=> [
                'required',
                'min:7',
                Rule::unique('clients')->ignore($this->client),
            ],
            'phone_1'=>'required|min:7',
            'phone_2'=>'nullable|min:7',
            'contact_name'=>'required|min:4',
            'cityId'=>'required|exists:cities,id',
//            'nomenclature_main'=> [
//                Rule::unique('addresses')->where( function ($query) {
//                    return $query->where('nomenclature_main',$this->nomenclature_main)
//                        ->where('number_main', $this->number_main)
//                        ->where('number',$this->number)
//                        ->where('city_id',$this->cityId);
//                })->ignore($this->address_id,'id'),'nullable'
//            ]

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'headquarter_name.required' => 'El nombre es requerido.',
            'name.unique' => 'El nombre ya está en uso.',
            'headquarter_name.unique' => 'El nombre ya está en uso.',
            'contact_name.required' => 'El contacto es requerido.',
            'phone_1.required' => 'El teléfono es requerido.',
            'cityId.required' => 'La ciudad es requerida.',
            'cityId.exists' => 'La ciudad es requerida.',
            'phone_2.min' => 'El teléfono debe contener al menos 7 números.',
            'phone_2.max' => 'El teléfono debe contener máximo 10 números.',
            'phone_1.min' => 'El teléfono debe contener al menos 7 númeross.',
            'phone_1.max' => 'El teléfono debe contener máximo 10 números.',
            'name.min' => 'El nombre debe tener al menos  4 caracteres.',
            'contact_name.min' => 'El contacto debe tener al menos  4 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.regex' => 'El email no es valido.',
            'nit.required' => 'El nit es requerido.',
            'nit.min' => 'El nit debe contener al menos 7 númeross.',
            'nit.unique' => 'El NIT proporcionado ya está en uso.',
            'nomenclature_main.unique' => 'La dirección proporcionado ya está en uso.',

        ];

    }
}
