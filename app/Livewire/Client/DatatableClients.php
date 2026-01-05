<?php

namespace App\Livewire\Client;

use App\Helper\HandleStatus;
use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableClients extends Component
{
    use WithPagination;
    public $heads,$counter, $page;

    //check if use cache.
    public $test_search = false;
    public $query = '', $amount = 10;


    public function mount()
    {
        $this->counter = 1;
        $this->heads = ['Items','Nombre*','Documento*','Contacto','Teléfono','Email*','Estado','Acciones'];

    }

    public function search()
    {
        $this->test_search = false;
        $this->resetPage();
    }

    public function render()
    {
        $clients =  $this->get_clients();
        if( !$this->test_search ) $this->test_search = true;
        return view('livewire.client.datatable',['clients'=>$clients]);
    }



    protected function get_clients_cache()
    {
        $key = $this->page ? 'clients'. $this->page : 'clients';
        $clients = null;
        if( !$this->test_search ){
            $clients =   $this->get_clients();
            Cache::delete($key);
            Cache::put($key, $clients, now()->addHours(12) );
        }else if( Cache::has($key) || $this->test_search){
            $clients =  Cache::get($key);
        }
        return $clients;

    }

    protected function get_clients()
    {
        $queries = trim($this->query);
        return Client::select('clients.id','clients.slug','clients.name','clients.status',
            'clients.nit',
            'headquarters.email',
            'headquarters.phone_1',
            'headquarters.contact_name')
            ->join('headquarters','clients.id','=','headquarters.client_id')
            ->where('headquarters.main',true)
            ->where(function($query) use ($queries) {
                $query->orWhere('clients.name', 'like', '%'.$queries.'%')
                    ->orWhere('clients.nit', 'like', '%'.$queries.'%')
                    ->orWhere('headquarters.email', 'like', '%'.$queries.'%');
            })->orderBy('id','desc')
            ->simplePaginate($this->amount);
    }

    public function updatingPaginators($page, $pageName)
    {
        $this->page = $page;
    }

    public function status( Client $client )
    {
        $this->test_search = false;
        HandleStatus::handle_status($client, 'El cliente');
    }




}
