<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    /**
     * Responde con una coleccion de recursos paginada, incluyendo la
     * metadata de paginacion (pagina actual, total, etc.).
     *
     * Anidar un AnonymousResourceCollection directamente en el arreglo de
     * `success()` pierde el `meta`/`links` que Laravel solo agrega cuando la
     * coleccion se retorna como respuesta de nivel superior; por eso aqui se
     * extraen explicitamente antes de envolverlos en el sobre estandar.
     */
    protected function successPaginated(AnonymousResourceCollection $resourceCollection, string $message = 'Operacion exitosa'): JsonResponse
    {
        $payload = $resourceCollection->response()->getData(true);

        return $this->success([
            'items' => $payload['data'],
            'meta' => $payload['meta'],
        ], $message);
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
