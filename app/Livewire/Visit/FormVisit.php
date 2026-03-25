<?php

namespace App\Livewire\Visit;

use App\Models\Client;
use App\Models\Headquarter;
use App\Models\VisitReason;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FormVisit extends Component
{
    public $client_id = '';
    public $headquarter_id = '';
    public $visit_reason_id = '';
    public $date = '';
    public $observations = '';
    public $start_time = '';
    public $end_time = '';

    public $clients_list = [];
    public $headquarters_list = [];
    public $visit_reasons_list = [];

    public function mount(): void
    {
        $this->loadClients();
        $this->loadVisitReasons();
    }

    protected function loadClients(): void
    {
        $this->clients_list = Client::getClients()->orderBy('name')->get();
    }

    protected function loadVisitReasons(): void
    {
        $this->visit_reasons_list = VisitReason::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function updatedClientId($value): void
    {
        $this->headquarter_id = '';
        if (empty($value)) {
            $this->headquarters_list = [];
            return;
        }
        $this->headquarters_list = Headquarter::where('client_id', $value)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    public function store(): void
    {
        $this->validate();



    }

    public function rules(): array
    {
        return [
            'client_id' => 'exists:clients,id',
            'headquarter_id' => 'exists:headquarters,id',
            'visit_reason_id' => [
                'required',
                Rule::exists('visit_reasons', 'id')->where('status', true),
            ],
            'date' => 'required|date',
            'observations' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.exists' => 'El cliente seleccionado no es válido.',
            'headquarter_id.exists' => 'La sucursal seleccionada no es válida.',
            'visit_reason_id.required' => 'La razón de visita es requerida.',
            'visit_reason_id.exists' => 'La razón de visita seleccionada no es válida o está inactiva.',
            'date.required' => 'La fecha es requerida.',
            'date.date' => 'La fecha no tiene un formato válido.',
            'observations.string' => 'Las observaciones deben ser texto.',
            'start_time.required' => 'La hora inicial es requerida.',
            'start_time.date_format' => 'La hora inicial debe tener formato HH:MM.',
            'end_time.required' => 'La hora final es requerida.',
            'end_time.date_format' => 'La hora final debe tener formato HH:MM.',
        ];
    }

    public function render()
    {
        return view('livewire.visit.form');
    }
}
