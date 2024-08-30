<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WarmupMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sub;
    public $message;
    /**
     * Create a new message instance.
     */
    public function __construct($sub,$msg)
    {
        $this->sub = $sub;
        $this->message = $msg;  
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: strip_tags($this->sub),
        );
    }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }
    public function build(){
        return $this->view('WarmupMail')->with([
            'sub'=>$this->sub,
            'msg'=>$this->message,
        ]);
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
