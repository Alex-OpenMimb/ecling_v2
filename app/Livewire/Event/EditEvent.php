<?php

namespace App\Livewire\Event;

use App\Helper\GeneralHelper;
use App\Models\Event;
use App\Models\EventUser;
use App\Services\Event\EventServices;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;
use Closure;

class EditEvent  extends ModalComponent
{

    public Event $event;

    #[Locked]
    public $id;

    public $date, $start_hour, $end_hour;

    public function mount(Event $event  )
    {
        $this->event = $event;
        $this->fill(
            $event->only('date','start_hour','end_hour','id')
        );

        $this->date = Carbon::parse($this->date)->format('Y-m-d');
    }



    public function render()
    {
        return view('livewire.event.edit');
    }

    public function update()
    {
        $this->validate();

        if( $this->start_hour === $this->end_hour ) return toastr()->error('La hora de inicio y la hora de finalización no pueden ser iguales','Error');
        if( $this->start_hour > $this->end_hour ) return toastr()->error('La hora de inicio debe ser anterior a la hora de finalización.','Error');

        //Validate if some  user has events already.
        $user_list = EventUser::where('event_id',$this->id)->select('user_id')->pluck('user_id')->toArray();
        $data = [
            'start_hour' =>$this->start_hour,
            'end_hour' =>$this->end_hour
        ];

        $user_name =   EventServices::validate_user_date( $user_list, $this->date, $data  );

        if( count($user_name) > 0 ){
            $users =  GeneralHelper::concatenate($user_name);
            $message =  $users . ' tienen un evento programado para esta fecha y hora.' ;
            return  toastr()->error($message, 'Error');
        }

        $this->event->date = $this->date;
        $this->event->start_hour = $this->start_hour;
        $this->event->end_hour = $this->end_hour;
        $this->event->save();
        $this->closeModal();
        $this->dispatch( 'reload_events' );


    }



    protected function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                function (string $attribute, mixed $value, Closure $fail) {
                    $date = Carbon::parse($value);
                    if (!$date->isToday() && $date->isBefore(Carbon::now())) {
                        $fail('La fecha debe ser hoy o una fecha futura.');
                    }
                },
            ],


        ];
    }

    protected function messages()
    {
        return [
            'date.required' => 'La fecha es requerida',

        ];
    }
}
