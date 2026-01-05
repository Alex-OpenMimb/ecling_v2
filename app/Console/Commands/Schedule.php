<?php

namespace App\Console\Commands;

use App\Models\Schedule as ScheduleModel;

use App\Services\Schedule\ServicesSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Schedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update up the status of schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->update_schedule();
    }



    protected function update_schedule()
    {
        $schedules = $this->get_active_client();
        $current_date = Carbon::now()->addDays( 0);
        $current_date = $current_date->format('Y-m-d');
        $this->info($current_date);
        foreach ($schedules as $schedule  ){
            $next_date = Carbon::parse($schedule->next_date);
            $days = Carbon::parse($current_date)->diffInDays( $next_date );
            $status = ServicesSchedule::handel_status( intval($days) );

            $schedule->days = $days;
            $schedule->status = $status;
            $schedule->save();
        }
    }



    protected function get_active_client()
    {
        return ScheduleModel::select('schedules.id','schedules.days','schedules.next_date','schedules.status')
            ->join('clients_has_equipments','schedules.client_has_equipment_id','=','clients_has_equipments.id')
            ->join('clients','clients_has_equipments.client_id','=','clients.id')
            ->where('clients.status',true)
            ->where('clients_has_equipments.status',true)
            ->where('clients_has_equipments.preventive_services',true)
            ->get();
    }
}
