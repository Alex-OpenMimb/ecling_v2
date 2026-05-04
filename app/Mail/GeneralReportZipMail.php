<?php

namespace App\Mail;

use App\Models\GeneralReport;
use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneralReportZipMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $service_order_serial;

    public int $reports_count;

    public string $logoPath;

    /**
     * @param  string  $zipRelativePath  Ruta relativa al disco por defecto (p. ej. document/massive/{uuid}/reportes.zip)
     */
    public function __construct(
        protected GeneralReport $generalReport,
        protected string $zipRelativePath,
        int $reportsCount
    ) {
        $this->service_order_serial = (string) ServiceOrder::where('id', $this->generalReport->service_order_id)
            ->value('serial');
        $this->reports_count = $reportsCount;
        $this->logoPath = public_path('image/logo/email_signature.jpg');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ordenes de servicio Technic Service (lote '.$this->reports_count.' reportes)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'livewire.generalReport.email',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->zipRelativePath)
                ->as('reportes.zip')
                ->withMime('application/zip'),
        ];
    }
}
