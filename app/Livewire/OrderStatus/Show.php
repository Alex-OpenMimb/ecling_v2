<?php

namespace App\Livewire\OrderStatus;

use App\Models\OrderStatus;
use Livewire\Component;

class Show extends Component
{
    public OrderStatus $orderStatus;

    public function mount(OrderStatus $orderStatus): void
    {
        $orderStatus->loadCount('serviceOrders');
        $this->orderStatus = $orderStatus;
    }

    public function render()
    {
        return view('livewire.orderStatus.show');
    }
}
