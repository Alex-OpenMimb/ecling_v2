<?php

namespace App\Livewire\VisitReason;

use App\Helper\HandleStatus;
use App\Models\VisitReason;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $heads, $counter, $page;
    public $test_search = false;
    public $query = '', $amount = 10;

    public function mount()
    {
        $this->counter = 1;
        $this->heads = ['Items', 'Razón', 'Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $visitReasons = $this->get_visit_reasons();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.visitReason.datatable', [
            'visitReasons' => $visitReasons,
        ]);
    }

    protected function get_visit_reasons()
    {
        $queries = trim($this->query);

        return VisitReason::select('id', 'name', 'description', 'status', 'created_at')
            ->when($queries, function ($query) use ($queries) {
                $query->where('name', 'like', '%'.$queries.'%');
            })
            ->orderByDesc('id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status(VisitReason $visitReason)
    {
        $this->test_search = false;
        HandleStatus::handle_status($visitReason, 'La razón');
    }
}

