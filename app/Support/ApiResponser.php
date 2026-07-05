<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * Estandariza las respuestas JSON de la API con un formato consistente de
 * exito/error, para que todos los endpoints respondan de la misma manera.
 */
trait ApiResponser
{
    protected function success(mixed $data = null, string $message = 'Operacion exitosa', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message = 'Ha ocurrido un error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
