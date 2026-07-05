<?php

namespace Database\Seeders;

use App\Models\SolicitudTecnica;
use Illuminate\Database\Seeder;

class SolicitudTecnicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SolicitudTecnica::factory()->count(20)->create();
    }
}
