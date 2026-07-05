<?php

namespace Database\Factories;

use App\Enums\EstadoSolicitud;
use App\Enums\Prioridad;
use App\Models\SolicitudTecnica;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SolicitudTecnica>
 */
class SolicitudTecnicaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente' => $this->faker->company(),
            'titulo' => $this->faker->sentence(6),
            'descripcion' => $this->faker->paragraph(),
            'prioridad' => $this->faker->randomElement(Prioridad::cases())->value,
            'estado' => $this->faker->randomElement(EstadoSolicitud::cases())->value,
        ];
    }
}
