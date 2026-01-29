<?php

namespace App\Livewire\User;

use App\Helper\GeneralHelper;
use App\Models\User;
use App\Services\Image\HandelImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileUser extends Component
{
    use WithFileUploads;

    public $urlImage,$slug,$image,$name,$phone,$email,$user, $password, $passwordIncoming, $newPassword,$role;

    public $photo_flag;
    #[Locked]
    public $id;


    public function mount()
    {
        $user = auth()->user();
        //Get url of image from local storage
        $this->urlImage =  $user->url_image ? asset('storage/' . $user->url_image) : null;
        $this->fill(
            $user->only( 'name','phone','email','id','password','slug'),
        );


    }



    public function render()
    {
        $this->role = auth()->user()->roles->first()->name;
        return view('livewire.user.profile',['role' => $this->role]);
    }


    public function update_user(User $user)
    {
        $this->validate();
        $user->update( [
            'name' =>$this->name,
            'slug' => Str::slug($this->name,'-'),
            'email' =>$this->email,
            'phone' =>$this->phone,

        ]);
        $this->name  = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        toastr()->success('Los datos se han actualizado con éxito!', 'Felicitaciones');
        return back();
    }


    public function update_password(User $user )
    {

        if (Hash::check($this->passwordIncoming, $this->password)) {
            $this->validate(
                [
                    'newPassword'=> [
                        'required',
                        'min:8',
                        'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/',
                    ]
                ]
            );

            $user->update(['password'=>Hash::make( $this->newPassword ) ]);
            $this->dispatch('clear_password');
            toastr()->success('Contraseña actualizada con éxito!', 'Felicitaciones');
            return back();
        } else {
            toastr()->error('Oops! La contraseña ingresada no es correcta!', 'Error!');
        }
    }

    public function update_photo( User $user )
    {

        //Validate photo incoming
        $error_message = 'La foto aún no ha sido cargada';
        if( $this->photo_flag && !$this->image ) {
            return  toastr()->error($error_message, 'Opps!');
        }
        $this->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|dimensions:max_width=2000,max_height=2000',
        ]);

        $extension = $this->image->extension();
        $path = $this->image->storeAs('image/profile',$this->id.'.'.$extension,'public');

        $user->update(['url_image' => $path]);
        //Get url of image from local storage

        $this->urlImage =  asset('storage/' . $path);
        toastr()->success('Foto  actualizada con éxito!', 'Felicitaciones');

        $this->dispatch('reload');

        return back();
    }




    protected function deletePhoto( $photoName )
    {
        Storage::disk('public')->delete('image/profile/'.$photoName);
    }


    public function rules()
    {
        return [
            'name' => 'required|min:5',
            'email' => [
                'required',
                'regex:/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('users')->ignore($this->id),
            ],
            'phone'=> 'numeric|min:10'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.min' => 'El nombre debe tener al menos de 5 caracteres.',
            'email.required' => 'El :attribute es requerido.',
            'email.regex' => 'El :attribute no es valido.',
            'phone.min' => 'El teléfono debe  tener al menos 10 números.',
            'newPassword.min' => 'La contraseña tener al menos 8 caracteres.',
            'newPassword.regex' => 'La contraseña tener al menos una letra mayúscula, una letra minúscula y un número.',
            'image.required' => 'El archivo es requerido.',
            'image.image' => 'El archivo debe ser formato jpg, png o jpeg.',
        ];
    }

}
