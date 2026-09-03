<?php

namespace App\Jobs;

use App\Mail\GeneralReportMail;
use App\Models\GeneralReport;
use App\Models\Headquarter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmail implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct( public  GeneralReport $general_report )
    {

    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            $headquarter_id = $this->general_report->headquarter_id;
            $email          = Headquarter::where('id', $headquarter_id)->select('name','email')->first()->email;
            Mail::to([$email,'operativo@ecling.co'])
                ->send( new  GeneralReportMail( $this->general_report ));
        }catch ( Throwable  $e ){
            Log::error('Error to send email: '. $e->getMessage());
        }


    }

}
