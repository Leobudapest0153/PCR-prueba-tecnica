<?php

namespace App\Enums;

/**
 * Valores permitidos para la prioridad de una solicitud tecnica.
 */
enum Prioridad: string
{
    case Baja = 'baja';
    case Media = 'media';
    case Alta = 'alta';
}
