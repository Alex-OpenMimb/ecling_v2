<?php

namespace App\Livewire\GeneralReport;

use App\Http\Controllers\GeneralReportController;
use App\Models\CoreConfig;
use App\Models\GeneralReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DatatableGeneralReport extends Component
{
    use WithPagination;
    public $heads,$counter, $amount= 10, $query, $stored;


    #[Locked]
    public $service_order_id;

    public function mount( $service_order_id  )
    {
        $this->service_order_id = $service_order_id;
        $this->counter = 1;
        $this->heads = ['Items','Reporte*','Tiempo','Enviado','Estado*','Preventivo','Correctivo',
           'Equipo','Acciones'];

    }


    #[On('general_report_load')]
    public function render()
    {
        $general_reports = $this->get_general_report()
            ->simplePaginate( $this->amount );
        return view('livewire.generalReport.datatable',['report_generals'=>$general_reports]);
    }

    protected function get_general_report()
    {
        $queries = trim($this->query);
        return  GeneralReport::select('general_reports.id',
            'general_reports.serial as serial_report',
            'general_reports.stored',
            'general_reports.status',
            'general_reports.preventive',
            'general_reports.corrective',
            'general_reports.equipment_class_id',
            'general_reports.time_spent',
            'general_reports.sent',
            'clients.name as client_name',
            'equipments.name as name_equipment',
            'clients_has_equipments.id as equipment_id')
            ->join('clients','general_reports.client_id','=','clients.id')
            ->join('clients_has_equipments','general_reports.client_has_equipment_id','=','clients_has_equipments.id')
            ->join('equipments','clients_has_equipments.equipment_id','=','equipments.id')
            ->where('general_reports.service_order_id',$this->service_order_id)
            ->where(function ($query) use ( $queries ){
                $query->where('general_reports.serial','like','%'.$queries. '%')
                    ->orWhere('clients_has_equipments.serial','like','%'.$queries. '%')
                    ->orWhere('clients_has_equipments.serial','like','%'.$queries. '%');

            });

    }

    public function error_msm_form($type)
    {
        $message = null;
        switch ( $type ){
            case 'form':
                $message = 'El reporte ya ha sido diligenciado o está cancelado.';
                break;
            case 'document':
                $message = 'Por favor, completa el reporte para acceder al documento.';
                break;
            case 'cancel':
                $message = 'La orden de servicio ha sido rechazada o declinada, no es posible diligenciar este reporte.';
                break;
            case 'email-stored':
                $message = 'Por favor, complete el reporte antes de enviarlo por correo electrónico.';
                break;

            case 'limited-time':
                $message = 'Se ha superado el tiempo limite para editar el reporte.';
                break;

        }
        return toastr()->error($message,'Error');
    }


    public function email_send_handle(GeneralReport $general_report )
    {
        if( $general_report->status === 'Cerrado' ){
            $general_report_controller = new GeneralReportController();
            $general_report->sent = 'Enviando';
            $general_report->save();
            $this->dispatch('general_report_load');
            $general_report_controller->send_pdf_document( $general_report );
            return toastr()->success('El email está en proceso de envío','Processando');
        }else{
            return  toastr()->error('Por favor, complete el reporte antes de enviarlo por correo electrónico.','Error');
        }

    }

    public function redirect_form_report( $service_order_id, GeneralReport $report )
    {
        $service_order_id = Crypt::encryptString($service_order_id);
        $report_id = Crypt::encryptString($report->id);
        $limitedTime = $this->checkLimitedTime( $report );

        $create_route = 'admin.general-reports.create.form';
        $edit_route   = 'admin.general-reports.edit.form';

        $role = auth()->user()->roles->first()->name;

        if( $role === 'Admin' || $role === 'Administrativo' || $role === 'Gerente General' ){
            if( $report->stored ){
                redirect()->route( $edit_route ,['service_order_id'=>$service_order_id,'general_report_id'=>$report_id]);
            }else{
                redirect()->route( $create_route ,['service_order_id'=>$service_order_id,'general_report_id'=>$report_id]);
            }
        }else{
            if( $report->status === 'Cancelado' ){
                $this->error_msm_form('form');
            }elseif ( $report->stored && !$limitedTime ){
                $this->error_msm_form('limited-time');
            }elseif ( $report->stored && $limitedTime ){
                redirect()->route( $edit_route ,['service_order_id'=>$service_order_id,'general_report_id'=>$report_id]);
            }
            elseif( !$report->stored ){
                redirect()->route( $create_route ,['service_order_id'=>$service_order_id,'general_report_id'=>$report_id]);
            }
        }

    }

    protected function checkLimitedTime( $report )
    {
        $currentTime  = Carbon::now();
        $storedTime   = $report->stored_time;
        $storedTime   = Carbon::parse($storedTime);
        $limitedHours =  CoreConfig::where( 'code','report_limited_hours' )->first();
        $limitedHours = $limitedHours ? $limitedHours->value : 24;
        if(  $storedTime->diffInHours($currentTime) > $limitedHours ) return false;
        else return true;
    }


    public function redirect_document(  GeneralReport $report )
    {
        $report_id = Crypt::encryptString($report->id);
        $this->dispatch('redirect_document',$report_id);
    }

}
