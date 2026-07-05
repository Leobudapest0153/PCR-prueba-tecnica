<?php

namespace App\Models;

use App\Enums\EstadoSolicitud;
use App\Enums\Prioridad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTecnica extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo (el nombre por convencion de Eloquent no
     * coincide con "solicitudes_tecnicas").
     */
    protected $table = 'solicitudes_tecnicas';

    /**
     * Nombres de columna usados por Eloquent para los timestamps automaticos,
     * segun el modelo de datos definido para la tabla.
     */
    const CREATED_AT = 'fecha_creacion';

    const UPDATED_AT = 'fecha_actualizacion';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cliente',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prioridad' => Prioridad::class,
            'estado' => EstadoSolicitud::class,
        ];
    }
}
