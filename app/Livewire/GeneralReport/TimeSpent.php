<?php

namespace App\Livewire\GeneralReport;

use App\Models\GeneralReport;
use LivewireUI\Modal\ModalComponent;

class TimeSpent   extends ModalComponent
{
    public GeneralReport $general_report;

    public $start_time, $end_time;

    public function mount(GeneralReport $general_report  )
    {
         $this->start_time = $general_report->start_time;
         $this->end_time  = $general_report->end_time;
    }

    public function render()
    {
        return view('livewire.generalReport.timeSpent');
    }
}
