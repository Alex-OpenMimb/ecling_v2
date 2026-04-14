<?php

namespace App\Livewire\GeneralReport;

use App\Models\GeneralReport;
use App\Models\ServiceOrder;
use App\Services\GeneralReport\GeneralReportData;
use App\Services\ServiceOrder\DataServiceOrder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class IndexGeneralReport  extends Component
{

    public $service_order_serial,$service_order_id, $url_back, $client_name, $headquarter_name;

    public ?int $client_id = null;

    public function mount(  $service_order_id )
    {
        $service_order_id       = Crypt::decryptString($service_order_id);
        $this->set_url();
        $this->service_order_id = ServiceOrder::find($service_order_id)->id;
        $this->service_order_serial = DataServiceOrder::get_service_order( $this->service_order_id );
        $this->client_name = GeneralReportData::get_client_name( $this->service_order_id  );
        $this->headquarter_name = GeneralReportData::get_headquarter_name( $this->service_order_id  );
        $this->client_id = GeneralReport::where('service_order_id', $this->service_order_id)->value('client_id');

    }

    public function render()
    {
        return view('livewire.generalReport.index');
    }

    protected function set_url()
    {
        $this->url_back = URL::previous();
        $array_url         = explode('/', $this->url_back);
        $previous = end( $array_url );
        if( $previous === 'services-order' ){
            $this->url_back = 'admin.service-order';
        }
        elseif( $previous === 'planner' ){
            $this->url_back = 'admin.planner';
        }else{

            $this->url_back = 'admin.service-order';
        }
    }
}
