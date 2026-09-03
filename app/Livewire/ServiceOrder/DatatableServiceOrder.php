<?php

namespace App\Livewire\ServiceOrder;

use App\Helper\GeneralHelper;
use App\Models\ClientEquipment;
use App\Models\ClientsEquipments;
use App\Http\Controllers\GeneralReportController;
use App\Models\GeneralReport;
use App\Models\ServiceOrder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableServiceOrder  extends Component
{

    use WithPagination;
    public $heads, $counter = 1, $amount =10,$query,$search_users;
    public $filter_client = '', $filter_equipment = '';

    public ?int $clientId = null;

    /** @var list<int> */
    public array $selectedOrderIds = [];

    public function mount(?int $clientId = null)
    {
        $this->clientId = $clientId;
        $this->heads = ['Items','','Creador','Serial*','Equipo','Cliente*','Sucursal*','Actividad*','Observaciones','Estado*','Acciones'];
        $this->search_users =  GeneralHelper::set_auth_users();

    }


    #[On('reload_service_orders')]
    public function render()
    {
        $service_order = $this->get_service_order()
          //  ->get();
            ->orderBy('service_order.serial','desc')
            ->simplePaginate($this->amount);

        return view('livewire.serviceOrder.datatable',[
            'orders'=> $service_order
        ]);
    }




    protected function get_service_order()
    {
        $queries = trim($this->query);
          $sub_query =  ServiceOrder::select('service_orders.id',
            'service_orders.serial',
            'service_orders.status',
            'service_orders.observations',
            'service_orders.activity',
            'schedules.client_has_equipment_id as schedule_equipment_id',
            'clients_equipments_correctives.client_has_equipment_id as corrective_equipment_id',
            'service_orders.user_id')
            ->leftJoin('schedules_has_service_orders','service_orders.id','schedules_has_service_orders.service_order_id')
            ->leftJoin('schedules','schedules_has_service_orders.schedule_id','schedules.id')
            ->leftJoin('corrective_services','service_orders.id','corrective_services.service_order_id')
            ->leftJoin('clients_has_equipments','schedules.client_has_equipment_id','clients_has_equipments.id')
            ->leftJoin('clients_equipments_correctives','corrective_services.id','clients_equipments_correctives.corrective_service_id')
            ->groupBY(
                'service_orders.id',
                'service_orders.serial',
                'service_orders.status',
                'service_orders.observations',
                'service_orders.activity',
                'schedules.client_has_equipment_id',
                'clients_equipments_correctives.client_has_equipment_id',
                'service_orders.user_id'
            );

        return ClientsEquipments::rightJoinSub($sub_query, 'service_order',function (JoinClause $join){
            $join->on('clients_has_equipments.id','=','service_order.schedule_equipment_id')
                ->orOn('clients_has_equipments.id','=','service_order.corrective_equipment_id');
        })->leftJoin('clients',function (JoinClause $join){
            $join->on('clients_has_equipments.client_id','=','clients.id');

        })->join('equipments','clients_has_equipments.equipment_id','equipments.id')
            ->join('headquarters', 'clients_has_equipments.headquarter_id', '=', 'headquarters.id')
            ->join('users','service_order.user_id','users.id')
            ->leftJoin('service_orders_has_users','service_order.id','=','service_orders_has_users.service_order_id')
            ->where(function ($query){
                $query->whereIn('service_order.user_id',$this->search_users)
                    ->orWhereIn('service_orders_has_users.user_id',$this->search_users);
            })->where(function ($query) use ($queries){
                $query->where('service_order.serial','like','%'. $queries. '%')
                    ->orWhere('clients.name','like','%'. $queries. '%')
                    ->orWhere('service_order.activity','like','%'. $queries. '%')
                    ->orWhere('headquarters.name','like','%'. $queries. '%')
                    ->orWhere('service_order.status','like','%'. $queries. '%');
            })->when($this->clientId, function ($query) {
                $query->where('clients.id', $this->clientId);
            })->when($this->filter_client, function ($query) {
                $filter_client = trim($this->filter_client);
                if ($filter_client) {
                    $query->where('clients.name', 'like', '%' . $filter_client . '%');
                }
            })->when($this->filter_equipment, function ($query) {
                $filter_equipment = trim($this->filter_equipment);
                if ($filter_equipment) {
                    $query->where('equipments.name', 'like', '%' . $filter_equipment . '%');
                }
            })->distinct()
            ->select('service_order.id',
                'service_order.serial',
                'clients_has_equipments.id as client_equipment_id',
                'equipments.name as equipments_name',
                'service_order.status',
                'clients.name',
                'headquarters.id as headquarter_id',
                'headquarters.name as headquarter_name',
                'service_order.observations',
                'service_order.activity',
                 'users.name as user_name');


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

    public function redirect_general_report( $order_id )
    {
        $order_id = Crypt::encryptString($order_id);
        redirect()->route('admin.general-reports',['service_order_id'=>$order_id]);
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

    /**
     * Al menos una orden seleccionada en el listado.
     */
    protected function validateAtLeastOneOrderSelected(): bool
    {
        return count(array_filter(array_map('intval', $this->selectedOrderIds))) > 0;
    }

    /**
     * Todas las órdenes seleccionadas existen en el listado filtrado y comparten la misma sede (headquarter).
     */
    protected function validateSelectedOrdersSameHeadquarter(): bool
    {
        $ids = array_values(array_unique(array_map('intval', array_filter($this->selectedOrderIds))));
        if ($ids === []) {
            return false;
        }

        $rows = $this->get_service_order()
            ->whereIn('service_order.id', $ids)
            ->select(['service_order.id', 'headquarters.id as headquarter_id'])
            ->get()
            ->unique('id');

        if ($rows->count() !== count($ids)) {
            return false;
        }

        return $rows->pluck('headquarter_id')->unique()->count() === 1;
    }

    public function massiveSend(): void
    {
        if (! $this->validateAtLeastOneOrderSelected()) {
            toastr()->error('Debe seleccionar al menos una orden de servicio.', 'Error');

            return;
        }

        if (! $this->validateSelectedOrdersSameHeadquarter()) {
            toastr()->error('Todas las órdenes seleccionadas deben pertenecer a la misma sede.', 'Error');

            return;
        }

        $serviceOrderIds = array_values(array_unique(array_map('intval', array_filter($this->selectedOrderIds))));

        $generalReports = GeneralReport::query()
            ->whereIn('service_order_id', $serviceOrderIds)
            ->orderBy('id')
            ->get(['id', 'service_order_id', 'serial']);

        $generalReportIds = $generalReports->pluck('id')->all();

        if ($generalReportIds === []) {
            toastr()->error('No hay reportes generales asociados a las órdenes seleccionadas.', 'Error');

            return;
        }

        try {
            app(GeneralReportController::class)->send_pdf_documents_zip($generalReportIds);
        } catch (\Throwable $e) {
            Log::error('Envío masivo reportes: '.$e->getMessage(), ['exception' => $e]);

            toastr()->error('No se pudo procesar el envío masivo. Intente de nuevo o contacte soporte.', 'Error');

            return;
        }

        toastr()->success('El envío masivo por correo (ZIP) quedó en cola.', 'Envío masivo');
    }

}
