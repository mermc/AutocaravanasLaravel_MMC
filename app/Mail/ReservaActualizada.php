<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaActualizada extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    public function build()
    {
        return $this->from('administracion@milanmc.me', 'Autocaravanas Milan')
                    ->subject('Modificación en tu reserva')
                    ->view('emails.reserva_actualizada');
    }
}