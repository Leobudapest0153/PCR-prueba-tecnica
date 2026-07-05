<?php

use App\Enums\EstadoSolicitud;
use App\Enums\Prioridad;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes_tecnicas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('prioridad', 20);
            $table->string('estado', 20)->default(EstadoSolicitud::Pendiente->value);

            // Se nombran igual que en el modelo de datos del enunciado y se
            // mapean como CREATED_AT/UPDATED_AT en el modelo Eloquent para
            // seguir aprovechando el manejo automatico de timestamps.
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->nullable();
        });

        // Refuerzo a nivel de base de datos de los valores permitidos, ademas
        // de la validacion que ya se hace en la capa de aplicacion.
        $prioridades = collect(Prioridad::cases())->map(fn ($caso) => "'{$caso->value}'")->implode(',');
        $estados = collect(EstadoSolicitud::cases())->map(fn ($caso) => "'{$caso->value}'")->implode(',');

        DB::statement("ALTER TABLE solicitudes_tecnicas ADD CONSTRAINT chk_solicitudes_tecnicas_prioridad CHECK (prioridad IN ({$prioridades}))");
        DB::statement("ALTER TABLE solicitudes_tecnicas ADD CONSTRAINT chk_solicitudes_tecnicas_estado CHECK (estado IN ({$estados}))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_tecnicas');
    }
};
