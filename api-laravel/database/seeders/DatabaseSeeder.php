<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Arrecadacoes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        $tributos = ['IPTU', 'ISS', 'ITBI'];
        $ano = now()->year;
        $mesAtual = now()->month;

        foreach ($tributos as $tributo) {
            foreach (range(1, $mesAtual) as $mes) {
                Arrecadacoes::factory()->create([
                    'tributo' => $tributo,
                    'mes' => $mes,
                    'ano' => $ano,
                    'valor' => fake()->randomFloat(2, 999, 100000)
                ]);
            }
        }
    }
}
