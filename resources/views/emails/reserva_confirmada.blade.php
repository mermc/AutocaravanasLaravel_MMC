<p>Hola {{ $reserva->user->name }},</p>
<p>Tu reserva ha sido confirmada.</p>
<ul>
    <li>Caravana: {{ $reserva->caravana->nombre ?? $reserva->caravana_id }}</li>
    <li>Fecha de Inicio: {{ $reserva->fecha_inicio }}</li>
    <li>Fecha de Fin: {{ $reserva->fecha_fin }}</li>
    <li>Precio total: {{ $reserva->precio_total }} €</li>
    <li>Precio pagado: {{ $reserva->precio_pagado }} € . Si has reservado por la app se te contactará en 24 horas para hacerlo efectivo</li>
    <li>Te recordamos que el día de la llegada deberás abonar 150€ en concepto de fianza que se te devolverán al final de tu reserva.</li>

    <li>Estamos a tu disposición contestando a este email o en el teléfono 654654654</li>
</ul>
<p>¡Gracias por confiar en nosotros!</p>