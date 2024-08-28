<?php

namespace Modules\Mailer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AutomaticMassEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject, $contentView;
    public $host;
    public $port;
    
    public $encryption;
    public $username;
    public $password;
    public $transport;
    var $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $contentView,$data = null)
    {
        $this->host = env("MAIL_HOST");
        $this->port = env("MAIL_PORT");
        $this->encryption = env("MAIL_ENCRYPTION");
        $this->username = env("MAIL_USERNAME");
        $this->password = env("MAIL_PASSWORD");
        $this->transport = env("MAIL_TRANSPORT");


        $this->subject = $subject;
        $this->contentView = $contentView;
        $this->data = $data;
    }

     /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->contentView,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
