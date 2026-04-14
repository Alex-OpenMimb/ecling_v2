<?php

namespace App\Livewire\QuotationStatus;

use App\Models\QuotationStatus;
use Livewire\Component;

class Show extends Component
{
    public QuotationStatus $quotationStatus;

    public function mount(QuotationStatus $quotationStatus): void
    {
        $quotationStatus->loadCount('quotations');
        $this->quotationStatus = $quotationStatus;
    }

    public function render()
    {
        return view('livewire.quotationStatus.show');
    }
}
