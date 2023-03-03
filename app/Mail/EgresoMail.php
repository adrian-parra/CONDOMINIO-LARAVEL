<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EgresoMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $type = 0)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function build()
    {
        return $this->from('condominio@aquipide.com', "LOAL");
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        if ($this->type == 0) {
            $subject = 'Egreso Condominio: Hay un nuevo Egreso por confirmar';
        } else {
            $subject = 'Egreso Condominio: Hay una modificacion a Egreso por confirmar';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        if ($this->type == 1) {
            return new Content(
                view: 'mails.egreso.update_egreso_por_confirmar'
            );
        }

        return new Content(
            view: 'mails.egreso.nuevo_egreso_por_confirmar'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
