<?php

use Illuminate\Support\Facades\Route;

// Este proyecto es una API pura (ver routes/api.php); no sirve vistas Blade.
Route::get('/', fn () => response()->json([
    'success' => true,
    'message' => 'API de administracion de solicitudes tecnicas. Ver /api/v1.',
]));
