<?php

namespace App\Livewire\Visit;

use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Visit;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormManageVisit extends Component
{
    #[Locked]
    public ?Visit $visit = null;

    public string $client_id = '';

    public string $headquarter_id = '';

    public $clients_list = [];

    public $headquarters_list = [];

    public string $report = '';

    public bool $generate_quotation = false;

    public string $quotation_expiration_date = '';

    public string $quotation_description = '';

    public function mount(?Visit $visit = null): void
    {
        $this->visit = $visit;
        $this->clients_list = Client::getClients()->orderBy('name')->get();
        $this->headquarters_list = collect();

        if ($visit === null) {
            return;
        }

        $visit->loadMissing('visitReason');

        $this->report = $visit->report ?? '';

        if ($visit->client_id) {
            $this->client_id = (string) $visit->client_id;
            $this->headquarters_list = Headquarter::where('client_id', $visit->client_id)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        }

        if ($visit->headquarter_id) {
            $this->headquarter_id = (string) $visit->headquarter_id;
        }
    }

    public function updatedClientId($value): void
    {
        $this->headquarter_id = '';
        if ($value === '' || $value === null) {
            $this->headquarters_list = collect();

            return;
        }

        $this->headquarters_list = Headquarter::where('client_id', $value)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    public function render()
    {
        return view('livewire.visit.form-manage');
    }
}
