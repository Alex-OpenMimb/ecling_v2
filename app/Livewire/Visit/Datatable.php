<?php

namespace App\Livewire\Visit;

use App\Helper\HandleStatus;
use App\Models\Visit;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $heads;

    public $counter;

    public $page;

    public $test_search = false;

    public $query = '';

    public $amount = 10;

    public function mount()
    {
        $this->counter = 1;
        $this->heads = ['Items', 'Cliente', 'Sucursal', 'Razón', 'Fecha', 'Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $visits = $this->get_visits();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.visit.datatable', [
            'visits' => $visits,
        ]);
    }

    protected function get_visits()
    {
        $queries = trim($this->query);

        return Visit::query()
            ->select([
                'id',
                'client_name',
                'headquarter_name',
                'observations',
                'status',
                'visit_reason_id',
                'event_id',
                'created_at',
            ])
            ->with([
                'visitReason:id,name',
                'event:id,date',
            ])
            ->when($queries, function ($q) use ($queries) {
                $q->where(function ($q) use ($queries) {
                    $q->where('client_name', 'like', '%'.$queries.'%')
                        ->orWhere('headquarter_name', 'like', '%'.$queries.'%')
                        ->orWhere('observations', 'like', '%'.$queries.'%')
                        ->orWhereHas('visitReason', function ($q) use ($queries) {
                            $q->where('name', 'like', '%'.$queries.'%');
                        });
                });
            })
            ->orderByDesc('id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status(Visit $visit)
    {
        $this->test_search = false;
        HandleStatus::handle_status($visit, 'La visita');
    }
}
