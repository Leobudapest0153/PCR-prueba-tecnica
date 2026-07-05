<?php

namespace App\Http\Requests\SolicitudTecnica;

use App\Enums\EstadoSolicitud;
use App\Enums\Prioridad;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterSolicitudTecnicaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validacion para los filtros de listado (query string).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estado' => ['sometimes', Rule::enum(EstadoSolicitud::class)],
            'prioridad' => ['sometimes', Rule::enum(Prioridad::class)],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'estado.enum' => 'El estado debe ser uno de los siguientes: pendiente, en_proceso, resuelto.',
            'prioridad.enum' => 'La prioridad debe ser una de las siguientes: baja, media, alta.',
            'per_page.integer' => 'per_page debe ser un numero entero.',
            'per_page.min' => 'per_page debe ser al menos 1.',
            'per_page.max' => 'per_page no debe superar 100.',
        ];
    }
}
