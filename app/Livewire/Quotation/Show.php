<?php

namespace App\Livewire\Quotation;

use App\Models\Quotation;
use Livewire\Component;

class Show extends Component
{
    public Quotation $quotation;

    public function mount(Quotation $quotation): void
    {
        $this->quotation = $quotation;
    }

    public function render()
    {
        return view('livewire.quotation.show');
    }
}
