<?php

namespace App\Livewire\ServiceOrder;

use App\Helper\GeneralHelper;
use App\Models\Client;
use App\Models\ClientEquipment;
use App\Models\ClientsEquipments;
use App\Models\ServiceOrder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableClientServiceOrder  extends Component
{

    use WithPagination;
    public $heads, $counter = 1, $amount =10,$query,$search_users;
    public $filter_client = '', $filter_equipment = '';

    public function mount()
    {
        $this->heads = ['Items','Nombre*','Documento*','Contacto','Teléfono','Email*','Acciones'];

        $this->search_users =  GeneralHelper::set_auth_users();

    }


    #[On('reload_service_orders')]
    public function render()
    {
        $service_order = $this->get_service_client();
        return view('livewire.serviceOrder.client-datatable',[
            'orders'=> $service_order
        ]);
    }




    protected function get_service_client()
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


    public function search()
    {
        $this->resetPage();
    }

    public function updatedFilterClient()
    {
        $this->resetPage();
    }

    public function updatedFilterEquipment()
    {
        $this->resetPage();
    }

    public function error_message_order( $type )
    {
        if(  $type === 'reject' ){
            $message = 'Acción no permitda, el estado de esta orden de servicios esta cerrada, pendiente o  rechazada.';
        }elseif( $type==='status' ){
            $message = 'Acción no permitda, el estado de esta orden de servicio no es cerrada.';
        }
        return  toastr()->error($message, 'Error');
    }



}
