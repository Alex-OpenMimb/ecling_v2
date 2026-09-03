<?php

namespace App\Livewire\VisitReason;

use App\Models\VisitReason;
use Livewire\Component;

class Show extends Component
{
    public VisitReason $visitReason;

    public function mount(VisitReason $visitReason): void
    {
        $this->visitReason = $visitReason;
    }

    public function render()
    {
        return view('livewire.visitReason.show');
    }
}

