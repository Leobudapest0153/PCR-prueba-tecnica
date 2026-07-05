<?php

namespace App\Services;

use App\Enums\EstadoSolicitud;
use App\Models\SolicitudTecnica;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SolicitudTecnicaService
{
    /**
     * Crea una nueva solicitud tecnica.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crear(array $datos): SolicitudTecnica
    {
        return SolicitudTecnica::create([
            'cliente' => $datos['cliente'],
            'titulo' => $datos['titulo'],
            'descripcion' => $datos['descripcion'],
            'prioridad' => $datos['prioridad'],
            'estado' => $datos['estado'] ?? EstadoSolicitud::Pendiente->value,
        ]);
    }

    /**
     * Lista las solicitudes tecnicas, permitiendo filtrar por estado y/o
     * prioridad, con soporte de paginacion.
     *
     * @param  array{estado?: string, prioridad?: string, per_page?: int}  $filtros
     */
    public function listar(array $filtros): LengthAwarePaginator
    {
        return SolicitudTecnica::query()
            ->when($filtros['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->when($filtros['prioridad'] ?? null, fn ($query, $prioridad) => $query->where('prioridad', $prioridad))
            ->latest('fecha_creacion')
            ->paginate($filtros['per_page'] ?? 15);
    }

    /**
     * Actualiza unicamente el estado de una solicitud tecnica existente.
     */
    public function actualizarEstado(SolicitudTecnica $solicitud, string $estado): SolicitudTecnica
    {
        $solicitud->update(['estado' => $estado]);

        return $solicitud->refresh();
    }
}
