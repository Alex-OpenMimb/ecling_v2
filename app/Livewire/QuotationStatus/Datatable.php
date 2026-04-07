<?php

namespace App\Livewire\QuotationStatus;

use App\Helper\HandleStatus;
use App\Models\QuotationStatus;
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
        $this->heads = ['Items', 'Nombre', 'Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $quotationStatuses = $this->get_quotation_statuses();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.quotationStatus.datatable', [
            'quotationStatuses' => $quotationStatuses,
        ]);
    }

    protected function get_quotation_statuses()
    {
        $queries = trim($this->query);

        return QuotationStatus::query()
            ->select('id', 'name', 'description', 'status', 'created_at')
            ->withCount('quotations')
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

    public function status(QuotationStatus $quotationStatus)
    {
        $this->test_search = false;
        HandleStatus::handle_status($quotationStatus, 'El estado');
    }
}
