<?php

namespace App\Mail;

use App\Models\GeneralReport;
use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class GeneralReportMail extends Mailable
{
    use Queueable, SerializesModels;

     public $service_order, $logoPath;

    /**
     * Create a new message instance.
     */
    public function __construct( protected GeneralReport  $generalReport )
    {
        $this->service_order = ServiceOrder::where('id',$this->generalReport->service_order_id)->select('serial')
            ->first('serial')->serial;
        $this->logoPath  = public_path('image/logo/email_signature.jpg');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Orden de servicio Technic Service '. $this->service_order,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'livewire.generalReport.email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage('document/'. $this->generalReport->id.'/reporteGeneral.pdf')
                ->as($this->service_order.'.pdf')
                ->withMime('application/pdf'),
        ];
    }


}
