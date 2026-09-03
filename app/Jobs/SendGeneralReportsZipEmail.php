<?php

namespace App\Jobs;

use App\Mail\GeneralReportZipMail;
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

class SendGeneralReportsZipEmail implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    /**
     * @param  list<int>  $generalReportIds
     */
    public function __construct(
        public GeneralReport $general_report,
        public string $zipRelativePath,
        public array $generalReportIds
    ) {}

    public function handle(): void
    {
        try {
            $headquarter_id = $this->general_report->headquarter_id;
            $email = Headquarter::where('id', $headquarter_id)->select('name', 'email')->first()->email;

            Mail::to([$email, 'ordenes.servicios@technicservicesas.com'])
                ->send(new GeneralReportZipMail(
                    $this->general_report,
                    $this->zipRelativePath,
                    count($this->generalReportIds)
                ));
        } catch (Throwable $e) {
            Log::error('Error to send zip email: '.$e->getMessage());
            throw $e;
        }
    }
}
