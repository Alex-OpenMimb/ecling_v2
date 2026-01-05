<?php

namespace App\Livewire\Store\Brand;

use App\Helper\HandleStatus;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableBrand extends Component
{
    use WithPagination;

    public $name;
    public $head, $counter = 1, $query;

    #[Locked]
    public $id;

    public function mount()
    {
        $this->head = ['item','Nombre*','Estado','Acciones'];
    }

    #[On('reload_brand')]
    public function render()
    {
        $queries = trim($this->query);
        $brands = Brand::select('brands.id','brands.name','brands.status')
            ->selectRaw('(SELECT COUNT(*) FROM equipments WHERE equipments.brand_id = brands.id AND equipments.deleted_at IS NULL) as equipments_count')
            ->where('brands.name','like','%'. $queries .'%')
            ->orderBy('brands.id','desc')
            ->simplePaginate(5);
        return view('livewire.store.brand.datatable',['brands'=>$brands]);
    }


    public function search()
    {
        $this->resetPage();
    }

    public function status( Brand $brand )
    {
        HandleStatus::handle_status($brand, 'La marca');
    }
}
