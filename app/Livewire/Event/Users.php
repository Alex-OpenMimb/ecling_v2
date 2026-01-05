<?php

namespace App\Livewire\Event;

use App\Models\EventUser;
use LivewireUI\Modal\ModalComponent;

class Users  extends ModalComponent
{


   public $user_list,$event_id;

    public function mount(  $event_id  )
    {
        $this->event_id = $event_id;
        $this->get_users();

    }

    public function render()
    {
        return view('livewire.event.users');
    }

    public function get_users()
    {
        $this->user_list = EventUser::join('users','events_has_users.user_id','=','users.id')
            ->whereIn('events_has_users.event_id',[$this->event_id])
            ->select('users.name')->get();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
}
