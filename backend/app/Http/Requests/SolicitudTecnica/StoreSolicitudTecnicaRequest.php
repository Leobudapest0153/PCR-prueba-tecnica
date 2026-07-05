<?php

namespace App\Http\Requests\SolicitudTecnica;

use App\Enums\EstadoSolicitud;
use App\Enums\Prioridad;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSolicitudTecnicaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente' => ['required', 'string', 'max:255'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'prioridad' => ['required', Rule::enum(Prioridad::class)],
            // El estado es opcional al crear: si no se envia, el servicio lo
            // establece como "pendiente" por defecto.
            'estado' => ['sometimes', Rule::enum(EstadoSolicitud::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cliente.required' => 'El cliente es obligatorio.',
            'cliente.max' => 'El cliente no debe superar los 255 caracteres.',
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.max' => 'El titulo no debe superar los 255 caracteres.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'prioridad.required' => 'La prioridad es obligatoria.',
            'prioridad.enum' => 'La prioridad debe ser una de las siguientes: baja, media, alta.',
            'estado.enum' => 'El estado debe ser uno de los siguientes: pendiente, en_proceso, resuelto.',
        ];
    }
}
