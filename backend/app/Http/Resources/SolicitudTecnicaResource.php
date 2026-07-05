<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\SolicitudTecnica
 */
class SolicitudTecnicaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente' => $this->cliente,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'prioridad' => $this->prioridad->value,
            'estado' => $this->estado->value,
            'fecha_creacion' => $this->fecha_creacion?->toISOString(),
            'fecha_actualizacion' => $this->fecha_actualizacion?->toISOString(),
        ];
    }
}
