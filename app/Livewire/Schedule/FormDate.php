<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use App\Services\Schedule\ServicesSchedule;
use App\Services\Schedules\ScheduleService;
use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;

class FormDate  extends ModalComponent
{

    public $next_date, $schedule;

    public function render()
    {
        return view('livewire.schedule.formDate');
    }


    public function mount(Schedule $schedule )
    {
        $this->next_date = $schedule->next_date;
        $this->next_date = Carbon::parse($this->next_date)->format('Y-m-d');
        $this->schedule = $schedule;

    }

    public function update()
    {
            $this->validate();

            //Updated the frequency when select the record in the table.
            ServicesSchedule::handle_next_date( $this->schedule, $this->next_date );
            $this->dispatch('update_frequency');
            toastr()->success('Fechas actualizada con éxito!', 'Felicitaciones');
            $this->closeModal();


    }




    public function rules()
    {
        return [
            'next_date' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'next_date.required' => 'La fecha es requerida.',
        ];
    }

}
