<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
public $message;
public $name;
public $email;
public $phone;
    /**
     * Create a new message instance.
     */
    public function __construct($studentname,$studentemail,$studentphone,$studentmessage)
    {
        $this->email=$studentemail;
        $this->phone=$studentphone;
        $this->name=$studentname;
        $this->message=$studentmessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'student.contact',
            with:[
                 'studentmessage'=>$this->message,
                 'name'=>$this->name,
                 'email'=>$this->email,
                 'phone'=>$this->phone
            ]
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

        ];
    }
}
