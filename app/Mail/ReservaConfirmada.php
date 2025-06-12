<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaConfirmada extends Mailable
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
                    ->subject('Confirmación de tu reserva')
                    ->view('emails.reserva_confirmada');
    }
}