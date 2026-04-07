<?php

namespace App\Livewire\Visit;

use App\Actions\Quotations\CreateOrUpdateQuotation;
use App\Actions\Utils\GenerateNextQuotationNumber;
use App\Actions\Visits\CreateOrUpdateVisits;
use App\Models\Client;
use App\Models\Event;
use App\Models\Headquarter;
use App\Models\QuotationStatus;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
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

    public function save(): void
    {
        if ($this->visit === null) {
            return;
        }

        $this->validate();
        if ($this->generate_quotation) {
            $this->createQuotation();
        }
        if ( $this->generate_quotation && ! QuotationStatus::query()->where('name', 'Abierta')->where('status', true)->exists()) {
            toastr()->error(
                'No existe un estado de cotización "Abierta" activo. Debes crear ese registro en quotation_status.',
                'Error'
            );

            return;
        }

        $savedVisit = CreateOrUpdateVisits::run([
            'id' => $this->visit->id,
            'event_id' => $this->visit->event_id,
            'client_id' => (int) $this->client_id,
            'headquarter_id' => (int) $this->headquarter_id,
            'visit_reason_id' => $this->visit->visit_reason_id,
            'observations' => $this->visit->observations,
            'status' => (bool) $this->visit->status,
            'report' => $this->report,
        ]);

        Event::query()->whereKey($savedVisit->event_id)->update(['closed' => true]);

        if ($this->generate_quotation) {
            $this->createQuotation();
        }

        toastr()->success('La visita se ha actualizado con éxito!', 'Felicitaciones');

        $this->redirect(route('admin.planner'));
    }

    protected function createQuotation(): void
    {
        $openStatus = QuotationStatus::query()
            ->where('name', 'Abierta')
            ->where('status', true)
            ->firstOrFail();

        $client = Client::query()->findOrFail((int) $this->client_id);
        $headquarter = Headquarter::query()->findOrFail((int) $this->headquarter_id);

        $number = GenerateNextQuotationNumber::run();

        CreateOrUpdateQuotation::run([
            'number' => $number,
            'date' => now(),
            'expiration_date' => Carbon::parse($this->quotation_expiration_date)->startOfDay(),
            'description' => $this->quotation_description,
            'status' => true,
            'client_name' => $client->name,
            'headquarter_name' => $headquarter->name,
            'quotation_status_name' => $openStatus->name,
            'quotation_status_id' => $openStatus->id,
            'client_id' => (int) $this->client_id,
            'headquarter_id' => (int) $this->headquarter_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'report' => ['required', 'string'],
            'client_id' => ['required', 'exists:clients,id'],
            'headquarter_id' => [
                'required',
                Rule::exists('headquarters', 'id')->where(fn ($query) => $query->where('client_id', $this->client_id)),
            ],
            'quotation_expiration_date' => Rule::when(
                $this->generate_quotation,
                ['required', 'date', 'after:today']
            ),
            'quotation_description' => Rule::when(
                $this->generate_quotation,
                ['required', 'string']
            ),
        ];
    }

    public function messages(): array
    {
        return [
            'report.required' => 'El reporte es obligatorio.',
            'report.string' => 'El reporte debe ser texto.',
            'client_id.required' => 'El cliente es obligatorio.',
            'client_id.exists' => 'El cliente seleccionado no es válido.',
            'headquarter_id.required' => 'La sucursal es obligatoria.',
            'headquarter_id.exists' => 'La sucursal seleccionada no es válida o no corresponde al cliente.',
            'quotation_expiration_date.required' => 'La fecha de expiración es obligatoria.',
            'quotation_expiration_date.date' => 'La fecha de expiración no es válida.',
            'quotation_expiration_date.after' => 'La fecha de expiración debe ser posterior al día de hoy.',
            'quotation_description.required' => 'La descripción de la cotización es obligatoria.',
            'quotation_description.string' => 'La descripción debe ser texto.',
        ];
    }

    public function render()
    {
        return view('livewire.visit.form-manage');
    }
}
