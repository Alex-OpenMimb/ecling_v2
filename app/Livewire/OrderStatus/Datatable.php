<?php

namespace App\Livewire\OrderStatus;

use App\Models\OrderStatus;
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
        $this->heads = ['Items', 'Nombre', 'Código', 'Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $orderStatuses = $this->get_order_statuses();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.orderStatus.datatable', [
            'orderStatuses' => $orderStatuses,
        ]);
    }

    protected function get_order_statuses()
    {
        $queries = trim($this->query);

        return OrderStatus::query()
            ->select('id', 'name', 'code', 'description', 'state', 'created_at')
            ->withCount('serviceOrders')
            ->when($queries, function ($query) use ($queries) {
                $query->where(function ($query) use ($queries) {
                    $query->where('name', 'like', '%'.$queries.'%')
                        ->orWhere('code', 'like', '%'.$queries.'%');
                });
            })
            ->orderByDesc('id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status(OrderStatus $orderStatus)
    {
        $this->test_search = false;
        $orderStatus->state = ! $orderStatus->state;
        $orderStatus->save();
        $message = $orderStatus->state ? 'se activó' : ' se ha desactivado';
        toastr()->success('El estado de orden '.$message.' con éxito!', 'Felicitaciones');
    }
}
