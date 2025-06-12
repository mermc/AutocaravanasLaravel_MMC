<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaEliminada extends Mailable
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
                    ->subject('Tu reserva ha sido cancelada')
                    ->view('emails.reserva_eliminada');
    }
}