<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolicitudTecnica\FilterSolicitudTecnicaRequest;
use App\Http\Requests\SolicitudTecnica\StoreSolicitudTecnicaRequest;
use App\Http\Requests\SolicitudTecnica\UpdateEstadoSolicitudTecnicaRequest;
use App\Http\Resources\SolicitudTecnicaResource;
use App\Models\SolicitudTecnica;
use App\Services\SolicitudTecnicaService;
use Illuminate\Http\JsonResponse;

class SolicitudTecnicaController extends Controller
{
    public function __construct(
        private readonly SolicitudTecnicaService $solicitudTecnicaService,
    ) {}

    /**
     * GET /api/v1/solicitudes-tecnicas
     *
     * Lista las solicitudes tecnicas, permitiendo filtrar por estado y/o
     * prioridad mediante query string.
     */
    public function index(FilterSolicitudTecnicaRequest $request): JsonResponse
    {
        $solicitudes = $this->solicitudTecnicaService->listar($request->validated());

        return $this->success(
            SolicitudTecnicaResource::collection($solicitudes),
            'Solicitudes obtenidas correctamente.'
        );
    }

    /**
     * POST /api/v1/solicitudes-tecnicas
     *
     * Crea una nueva solicitud tecnica.
     */
    public function store(StoreSolicitudTecnicaRequest $request): JsonResponse
    {
        $solicitud = $this->solicitudTecnicaService->crear($request->validated());

        return $this->success(
            new SolicitudTecnicaResource($solicitud),
            'Solicitud tecnica creada correctamente.',
            201
        );
    }

    /**
     * GET /api/v1/solicitudes-tecnicas/{solicitudTecnica}
     *
     * Muestra el detalle de una solicitud tecnica.
     */
    public function show(SolicitudTecnica $solicitudTecnica): JsonResponse
    {
        return $this->success(
            new SolicitudTecnicaResource($solicitudTecnica),
            'Solicitud obtenida correctamente.'
        );
    }

    /**
     * PATCH /api/v1/solicitudes-tecnicas/{solicitudTecnica}/estado
     *
     * Actualiza unicamente el estado de una solicitud tecnica.
     */
    public function actualizarEstado(UpdateEstadoSolicitudTecnicaRequest $request, SolicitudTecnica $solicitudTecnica): JsonResponse
    {
        $solicitud = $this->solicitudTecnicaService->actualizarEstado(
            $solicitudTecnica,
            $request->validated('estado')
        );

        return $this->success(
            new SolicitudTecnicaResource($solicitud),
            'Estado de la solicitud actualizado correctamente.'
        );
    }
}
