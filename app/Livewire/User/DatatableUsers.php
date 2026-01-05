<?php

namespace App\Livewire\User;

use App\Helper\HandleStatus;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableUsers extends Component
{
    use WithPagination;


    public $heads, $counter = 1;
    public $query = '', $amount = 10;

    public function mount()
    {

        $this->heads = ['Items','Nombre*','Identificación*','Rol','Email*','Teléfono','Estado','Acciones'];

    }


    public function render()
    {

        $users = $this->get_users();
        return view('livewire.user.datatable',['users'=>$users]);

    }


    protected function get_users()
    {
        $query = trim($this->query);
        return  User::with('roles')
            ->oRwhere('name', 'like', '%'.$query.'%')
            ->oRwhere('document', 'like', '%'.$query.'%')
            ->oRwhere('email', 'like', '%'.$query.'%')
            ->select('id','name','phone','email','status','document','slug')
            ->notAdmin()
            ->orderBy('id','desc')->simplePaginate($this->amount);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function status( User $user )
    {
        HandleStatus::handle_status($user, 'El usuario');
    }
}
