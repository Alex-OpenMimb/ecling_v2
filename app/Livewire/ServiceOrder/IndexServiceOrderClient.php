<?php

namespace App\Livewire\ServiceOrder;

use App\Models\Client;
use Livewire\Component;

class IndexServiceOrderClient extends Component
{
    public Client $client;

    public ?string $phone = null;

    public ?string $email = null;

    public function mount(int $clientId): void
    {
        $this->client = Client::with(['headquarters' => fn ($q) => $q->where('main', true)])
            ->findOrFail($clientId);

        $headquarter = $this->client->headquarters->first();
        $this->phone = $headquarter?->phone_1;
        $this->email = $headquarter?->email;
    }

    public function render()
    {
        return view('livewire.serviceOrder.index-service-order-client');
    }
}
