<?php

namespace App\Livewire\Event;

use App\Models\EventUser;
use App\Models\VisitUser;
use LivewireUI\Modal\ModalComponent;

class Users  extends ModalComponent
{


   public $user_list,$event_id,$visit_id;

    public function mount( $event_id = null, $visit_id = null )
    {
        $this->event_id = $event_id;
        $this->visit_id = $visit_id;
        $this->get_users();

    }

    public function render()
    {
        return view('livewire.event.users');
    }

    public function get_users()
    {
        if ($this->visit_id) {
            $this->user_list = VisitUser::query()
                ->join('users', 'visits_users.user_id', '=', 'users.id')
                ->where('visits_users.visit_id', $this->visit_id)
                ->orderBy('users.name')
                ->select('users.name')
                ->get();

            return;
        }

        $this->user_list = EventUser::join('users','events_has_users.user_id','=','users.id')
            ->whereIn('events_has_users.event_id',[$this->event_id])
            ->select('users.name')->get();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
}
