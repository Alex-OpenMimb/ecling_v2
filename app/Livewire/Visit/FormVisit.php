<?php

namespace App\Livewire\Visit;

use App\Actions\Events\CreateEvent;
use App\Actions\Events\UpdateEvent;
use App\Actions\Visits\CreateVisitsUsers;
use App\Actions\Visits\CreateOrUpdateVisits;
use App\Actions\Visits\UpdateVisitsUsers;
use App\Models\Client;
use App\Models\Headquarter;
use App\Models\Visit;
use App\Models\VisitUser;
use App\Models\VisitReason;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class FormVisit extends Component
{
    #[Locked]
    public ?int $visit_id = null;

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

    public $users_list = [];
    public $users_ids = [];
    public Visit $visit;
    public function mount( Visit $visit ): void
    {
        $this->loadClients();
        $this->loadVisitReasons();
        $this->loadUsers();

        $this->fill(
            $visit->only( 'client_id','visit_reason_id','observations'),
        );
        if( $visit->id ){
            $this->visit_id = $visit->id;
            $this->loadSucursalFromVisit($visit);
            $this->loadEventDateAndHoursFromVisit($visit);
            $this->loadUsersFromVisitUsers($visit);
        }

    }

    /**
     * Carga la sucursal (headquarter) en el form y llena el listado de sucursales
     * según el cliente del registro actual.
     */
    protected function loadSucursalFromVisit(Visit $visit): void
    {
        $this->headquarter_id = $visit->headquarter_id ? (string) $visit->headquarter_id : '';

        if ($visit->client_id) {
            $this->headquarters_list = Headquarter::where('client_id', $visit->client_id)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']);
        } else {
            $this->headquarters_list = [];
        }
    }

    /**
     * Obtiene desde el event asociado la fecha y las horas.
     */
    protected function loadEventDateAndHoursFromVisit(Visit $visit): void
    {
        $visit->loadMissing('event');

        $event = $visit->event;

        $this->date = $event && $event->date
            ? (is_string($event->date) ? $event->date : $event->date->format('Y-m-d'))
            : '';

        $this->start_time = $event->start_hour ?? '';
        $this->end_time = $event->end_hour ?? '';
    }

    /**
     * Obtiene desde `visits_users` los usuarios asignados a esta visita
     * para marcarlos como checked en el listado.
     */
    protected function loadUsersFromVisitUsers(Visit $visit): void
    {
        $this->users_ids = VisitUser::where('visit_id', $visit->id)
            ->pluck('user_id')
            ->toArray();
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

    protected function loadUsers(): void
    {
        $this->users_list = User::query()
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
        if ($this->client_id === '') {
            $this->client_id = null;
        }
        if ($this->headquarter_id === '') {
            $this->headquarter_id = null;
        }

        $this->validate();


        $clientId = $this->client_id !== null ? (int) $this->client_id : null;
        $headquarterId = $this->headquarter_id !== null ? (int) $this->headquarter_id : null;

        if ($this->visit_id) {
            $visit = Visit::with('event')->findOrFail($this->visit_id);

            UpdateEvent::run($visit->event_id, [
                'date' => $this->date,
                'start_hour' => $this->start_time,
                'end_hour' => $this->end_time,
            ]);

            $visit = CreateOrUpdateVisits::run([
                'id' => $visit->id,
                'event_id' => $visit->event_id,
                'client_id' => $clientId,
                'headquarter_id' => $headquarterId,
                'visit_reason_id' => (int) $this->visit_reason_id,
                'observations' => $this->observations,
                'status' => $visit->status,
            ]);

            UpdateVisitsUsers::run($visit->id, $this->users_ids);

            toastr()->success('La visita se ha actualizado con éxito!', 'Felicitaciones');
        } else {
            $event = CreateEvent::run([
                'date' => $this->date,
                'start_hour' => $this->start_time,
                'end_hour' => $this->end_time,
                'activity' => 'Otra',
                'user_id' => auth()->id(),
            ]);

            $visit = CreateOrUpdateVisits::run([
                'event_id' => $event->id,
                'client_id' => $clientId,
                'headquarter_id' => $headquarterId,
                'visit_reason_id' => (int) $this->visit_reason_id,
                'observations' => $this->observations,
                'status' => true,
            ]);

            CreateVisitsUsers::run($visit->id, $this->users_ids);

            toastr()->success('La visita se ha registrado con éxito!', 'Felicitaciones');
        }

        $this->redirect(route('admin.visit.index'));
    }

    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'exists:clients,id'],
            'headquarter_id' => ['nullable', 'exists:headquarters,id'],
            'visit_reason_id' => [
                'required',
                Rule::exists('visit_reasons', 'id')->where('status', true),
            ],
            'users_ids' => ['required', 'array', 'min:1'],
            'users_ids.*' => ['integer', 'exists:users,id'],
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
            'users_ids.required' => 'Debes seleccionar al menos un usuario activo.',
            'users_ids.array' => 'Los usuarios seleccionados no son válidos.',
            'users_ids.min' => 'Debes seleccionar al menos un usuario activo.',
            'users_ids.*.exists' => 'Alguno de los usuarios seleccionados no es válido.',
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
