<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderUser;
use LivewireUI\Modal\ModalComponent;

class Users  extends ModalComponent
{

    #[Locked]
    public  $order_id;

    public $users,$creator;

    public function mount(  $order_id )
    {
        $this->order_id = $order_id;
        $this->users = $this->get_users();
        $this->get_creator();
    }

    public function render()
    {
        return view('livewire.serviceOrder.users');
    }

    protected function get_users()
    {
        return  ServiceOrderUser::join('users','service_orders_has_users.user_id','=','users.id')
            ->whereIn('service_order_id',[$this->order_id])
            ->select('users.name')->get();
    }

    protected function get_creator()
    {
        $this->creator = ServiceOrder::join('users','service_orders.user_id','=','users.id')
            ->where('service_orders.id','=',$this->order_id)->select('users.name')->first()->name;
    }


    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
}
