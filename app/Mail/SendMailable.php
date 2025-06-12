<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $message;
   
    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }
    public function build()
    {
        
        return $this->view('emails.send')
                    //->from($this->from)
                    //->cc($address, $name)
                    //->bcc($address, $name)
                    //->replyTo($address, $name)
                    ->subject($this->subject)
                    ->with([ 'body' => $this->message ]);
    }
}
