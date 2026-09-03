<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class FormUser extends Component
{
    public User $user;
    public $roles_list, $action;

    public $name;

    public $phone;

    public $roleId;
    public $status;
    public $email;
    public $password;
    public $document;

    public $slug;

    #[Locked]
    public $id;


    public function mount(User $user)
    {
        $this->user = $user;
        $this->fill(
            $user->only( 'name','phone','email','id','document'),
        );
        $this->roles_list = Role::whereNotIn('name',['Admin'])->get();
        if( $user->id ) $this->roleId = $user->roles->first()->id;
        $this->action = $user->id ? 'update':'create';

    }


    public function render()
    {
        return view('livewire.user.form');
    }

    public function updateOrStore( $actionType )
    {
        $this->slug = Str::slug($this->name, '-');
        $this->validate();
        if( $actionType === 'create' ){
            $this->validate([
                'password' => [
                    'required',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
                ],
            ]);
        }

        $find_id = ['id'=>$this->id];

        $data = [
            'name'              => $this->name,
            'slug'              => Str::slug($this->name,'-'),
            'email'             => $this->email,
            'document'          => $this->document,
            'phone'            => $this->phone,
        ];

        if( $actionType === 'create'){
            $data['password'] = Hash::make($this->password);
            $message = 'El usuario se ha creado con éxito!';
        } else{
            $message = 'Los datos se han actualizado con éxito!';
        }

        $user = User::updateOrCreate($find_id,$data);
        $name_role =  Role::find($this->roleId)->name;
        $user->syncRoles($name_role);
        toastr()->success($message, 'Felicitaciones');
        session()->flash('user-message', 'Usuario '. $message .' con éxito!');
        return redirect()->route('admin.users');

    }

    public function rules()
    {

        return [
            'name' => ['required', 'string', 'min:5',
             Rule::unique('users')->ignore($this->user)
            ],
            'email' => [
                'required',
                'regex:/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/',
                 Rule::unique('users')->ignore($this->user),
            ],
            'roleId'=>'required|numeric|in:2,3,4',
            'phone'=>'min:7|max:10',
            'document'=>[Rule::unique('users')->ignore($this->user),'nullable'],
            'slug' => [Rule::unique('users', 'slug')->ignore($this->user)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es requerido.',
            'name.unique' => 'El :attribute debe ser único.',
            'name.min' => 'El :attribute debe ser minimo de 5 caracteres.',
            'email.required' => 'El :attribute es requerido.',
            'email.unique' => 'El :attribute debe ser único.',
            'roleId.required' => 'El rol es requerido.',
            'email.regex' => 'El :attribute no es valido.',
            'password.regex' => 'La contraseña debe tener al menos una letra mayúscula, una minúscula y un número.',
            'password.required' => 'La contraseña es requerida.',
            'phone.min' => 'El teléfono debe tener al menos 7 números.',
            'phone.max' => 'El teléfono debe tener máximo 10 números.',
            'document.unique' => 'El documento ya está en uso.',
            'slug.unique' => 'Ya existe un usuario con este nombre.',

        ];
    }

}
