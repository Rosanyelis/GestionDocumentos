<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDocumentoNotify extends Mailable
{
    use Queueable, SerializesModels;

    // public $archivo;
    // public $urlfile;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->archivo = $archivo;
        // $this->urlfile = $urlfile;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'SoftGD - Tiene un documento que firmar',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.asignardocumento',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        // $url = public_path().$this->urlfile;
        return [
            // Attachment::fromStorage($url)
            //     ->as($this->archivo)
            //     ->withMime('application/pdf'),
        ];
    }
}
