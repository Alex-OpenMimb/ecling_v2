<?php

namespace App\Livewire\ClientEquipment;

use App\Helper\HandleStatus;
use App\Models\Client;
use App\Models\ClientEquipment;
use App\Models\ClientsEquipments;
use App\Models\Headquarter;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableClientEquipment  extends Component
{
    use WithPagination;
    public Headquarter $headquarter;
    public Client $client;

    public $heads, $counter = 1, $amount = 10, $query;

    public $headquarter_id, $client_id;

    public function mount(Headquarter $headquarter,Client $client )
    {
        $this->client = $client;
        $this->headquarter = $headquarter;
        $this->headquarter_id = $headquarter->id;
        $this->client_id = $client->id;
        $this->heads = ['Items','Nombre*','Modelo*','Marca*','Clase de equipo*','Estado','Preventivo'];
    }


    public function render()
    {
        $equipments = $this->get_equipments();
        return view('livewire.clientEquipment.datatable',['equipments'=>$equipments]);

    }



    protected function get_equipments()
    {
        $queries = trim($this->query);
        return ClientsEquipments::select('clients_has_equipments.id',
            'clients_has_equipments.status',
            'clients_has_equipments.preventive_services',
            'brands.name as brand_name',
            'equipments.name as equipment_name',
            'equipment_classes.name as equipment_class_name',
            'equipment_models.model as equipment_model',
          )->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->join('equipment_classes','equipments.equipment_class_id','=','equipment_classes.id')
            ->join('equipment_models','equipments.equipment_model_id','=','equipment_models.id')
            ->join('brands','equipments.brand_id','=','brands.id')
            ->where('clients_has_equipments.headquarter_id',$this->headquarter_id)
            ->where(function ($query) use ($queries){
                $query->orWhere('brands.name','like','%'. $queries. '%')
                ->orWhere('equipment_models.model','like','%'. $queries. '%')
                ->orWhere('brands.name','like','%'. $queries. '%')
                ->orWhere('equipments.name','like','%'. $queries. '%')
                ->orWhere('equipment_classes.name','like','%'. $queries. '%');

            })->orderBy('clients_has_equipments.id','desc')
            ->simplePaginate( $this->amount );
    }



    public function status( ClientsEquipments $equipment )
    {
        HandleStatus::handle_status($equipment, 'El equipo');
    }


    public function search()
    {
        $this->resetPage();
    }
}
