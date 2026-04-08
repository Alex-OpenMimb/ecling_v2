<?php

namespace App\Livewire\Visit;

use App\Models\Visit;
use Livewire\Component;

class Show extends Component
{
    public Visit $visit;

    public function mount(Visit $visit): void
    {
        $this->visit = $visit->load([
            'event',
            'visitReason',
            'client',
            'headquarter',
            'quotations.quotation_status',
            'quotations.client',
            'quotations.headquarter',
        ]);
    }

    public function render()
    {
        return view('livewire.visit.show');
    }
}
