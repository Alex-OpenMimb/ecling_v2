<?php

namespace App\Livewire\TitlePhoto;

use App\Helper\HandleStatus;
use App\Models\TitlePhoto;
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
        $this->heads = ['Items', 'Título','Estado', 'Acciones'];
    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $titlePhotos = $this->get_title_photos();

        if (! $this->test_search) {
            $this->test_search = true;
        }

        return view('livewire.titlePhoto.datatable', [
            'titlePhotos' => $titlePhotos,
        ]);
    }

    protected function get_title_photos()
    {
        $queries = trim($this->query);

        return TitlePhoto::select('id', 'title', 'status', 'created_at','slug')
            ->when($queries, function ($query) use ($queries) {
                $query->where('title', 'like', '%'.$queries.'%');
            })
            ->orderByDesc('id')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status(TitlePhoto $titlePhoto)
    {
        $this->test_search = false;
        HandleStatus::handle_status($titlePhoto, 'El título');
    }
}
