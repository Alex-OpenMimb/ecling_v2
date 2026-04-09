<?php

namespace App\Livewire\Quotation;

use App\Models\Quotation;
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

    public function mount(): void
    {
        $this->counter = 1;
        $this->heads = ['Items', 'Número', 'Fecha', 'Vencimiento', 'Cliente', 'Sede', 'Estado', 'Acciones'];
    }

    public function search(): void
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $quotations = $this->getQuotations();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.quotation.datatable', [
            'quotations' => $quotations,
        ]);
    }

    protected function getQuotations()
    {
        $queries = trim($this->query);

        return Quotation::query()
            ->select(
                'id',
                'number',
                'date',
                'expiration_date',
                'client_name',
                'headquarter_name',
                'quotation_status_name'
            )
            ->when($queries, function ($query) use ($queries) {
                $query->where(function ($q) use ($queries) {
                    $q->where('number', 'like', '%'.$queries.'%')
                        ->orWhere('client_name', 'like', '%'.$queries.'%')
                        ->orWhere('headquarter_name', 'like', '%'.$queries.'%')
                        ->orWhere('quotation_status_name', 'like', '%'.$queries.'%');
                });
            })
            ->orderByDesc('id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName): void
    {
        $this->page = $page;
    }
}
