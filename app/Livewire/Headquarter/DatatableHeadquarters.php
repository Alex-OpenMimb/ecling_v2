<?php

namespace App\Livewire\Headquarter;

use App\Models\Client;
use App\Models\Headquarter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableHeadquarters extends  Component
{
    use WithPagination;
    public  $heads,$name,$main,$address,$counter, $query='',$status, $amount = 10;

    #[Locked]
    public $client_id;
    public $client_slug;
    public function mount(Client $client )
    {
        $this->status = $client->status;
        $this->client_slug = $client->slug;
        $this->client_id = $client->id;
        $this->counter = 1;
        $this->heads = ['Items','Nombre*','Email','Contacto*','Ciudad*','Principal', 'Acciones'];

    }


    public function search()
    {
        $this->resetPage();
    }

    #[On('restart_table')]
    public function restart_table()
    {
        $this->query = '';
        $this->dispatch('clear_input');
    }




    public function render()
    {

        $headquarters = $this->get_headquarters();
        return view('livewire.headquarter.datatable',['headquarters'=>$headquarters]);

    }

    protected function get_headquarters()
    {
        $queries = trim($this->query);
        return Headquarter::select('headquarters.main','headquarters.slug', 'headquarters.email','headquarters.id','headquarters.contact_name','cities.name as city_name','headquarters.name as head_name')
            ->join('addresses','headquarters.address_id','=','addresses.id')
            ->join('cities','addresses.city_id','=','cities.id')
            ->where('client_id',$this->client_id)
            ->where(function ($query) use ($queries){
                $query->orWhere('headquarters.contact_name','like','%'.$queries.'%');
                $query->orWhere('cities.name','like','%'.$queries.'%');
                $query->orWhere('headquarters.name','like','%'.$queries.'%');
            })->orderBy('id','desc')
            ->simplePaginate($this->amount);
    }


    public function handle_main( Headquarter $headquarter )
    {
        $headquarter_id = $headquarter->id;
        $validator = $this->validate_main( $headquarter_id );
        if( empty( $validator ) ) {
             toastr()->error('Debes definir una sucuarsal como principal','Error');
            $this->dispatch('reload');
            return;
        }

        $this->unactive_main();
        $headquarter->main = !$headquarter->main;
        $headquarter->save();
        $this->render();
        $message = $headquarter->main ? 'se ha activada' : 'ha sido desactivada';
        toastr()->success('La sucursal  '. $message .' como principal con éxito!', 'Felicitaciones');
        $this->dispatch('reload');

    }


    protected function unactive_main()
    {
        Headquarter::where('client_id',$this->client_id)
            ->update([
                'main'=>false
            ]);
    }

    /*
     * Validate if there is no main headquarter
     */
    protected function validate_main( $headquarter_id )
    {
         return  Headquarter::where( 'client_id',$this->client_id )
                       ->whereNot( 'id', $headquarter_id )
                      ->where('main',true)->get()->toArray();
    }


    #[On('inactive_client_equipment')]
    public function show_error_msm()
    {
        return  toastr()->error('No es posible crear un equpipo porque el cliente está desactivado.', 'Error');

    }
}
