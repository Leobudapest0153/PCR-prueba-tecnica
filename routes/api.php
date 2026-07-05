<?php

use App\Http\Controllers\Api\V1\SolicitudTecnicaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('solicitudes-tecnicas', SolicitudTecnicaController::class)
        ->parameters(['solicitudes-tecnicas' => 'solicitudTecnica'])
        ->only(['index', 'store', 'show']);

    Route::patch('solicitudes-tecnicas/{solicitudTecnica}/estado', [SolicitudTecnicaController::class, 'actualizarEstado'])
        ->name('solicitudes-tecnicas.actualizar-estado');
});
