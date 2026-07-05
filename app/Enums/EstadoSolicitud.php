<?php

namespace App\Enums;

/**
 * Valores permitidos para el estado de una solicitud tecnica.
 */
enum EstadoSolicitud: string
{
    case Pendiente = 'pendiente';
    case EnProceso = 'en_proceso';
    case Resuelto = 'resuelto';
}
