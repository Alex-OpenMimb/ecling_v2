<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use App\Services\Schedule\ServicesSchedule;
use App\Services\Schedules\ScheduleService;
use LivewireUI\Modal\ModalComponent;

class FormFrequency extends ModalComponent
{



    public $frequency, $schedule, $schedule_list;

    public function mount(Schedule $schedule, $frequency )
    {
        $this->frequency = $frequency;
        $this->schedule = $schedule;


    }

    public function render()
    {
        return view('livewire.schedule.formFrequency');
    }



    public function update()
    {
            $this->validate();
            //Updated the frequency when select the record in the table.
            $this->update_by_id( $this->schedule );

            toastr()->success('Frecuencia actualizada con éxito!', 'Felicitaciones');
            $this->dispatch('update_frequency');
            $this->dispatch('restart_schedule_check');
            $this->closeModal();


    }


    public function update_by_id( $schedule )
    {
        $frequency = intval($this->frequency);
        $last_date = $schedule->last_date;
        //Calculated frequency;
        $data = ServicesSchedule::handle_frequency( $frequency, $last_date,$schedule );

        $next_date = $data['next_day'];
        $days  = $data['days'];
        $status = $data['status'];

        $schedule->frequency = $this->frequency;
        $schedule->next_date = $next_date;
        $schedule->days = $days;
        $schedule->status = $status;
        $schedule->save();


    }


    public function rules()
    {
        return [
            'frequency' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ];
    }

    public function messages()
    {
        return [
            'frequency.required' => 'La frecuencia es requerido.',
            'frequency.numeric' => 'El valor no es valido, ingrese un número.',
            'frequency.gt' => 'El valor no es valido.',
        ];
    }


    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
}
