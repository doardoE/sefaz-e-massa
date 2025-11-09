<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Arrecadacoes>
 */
class ArrecadacoesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $combinacoesUsadas = [];

        do {
            $tributo = fake()->randomElement(['IPTU', 'ISS', 'ITBI']);
            $mes = fake()->numberBetween(1, 12);
            $ano = fake()->numberBetween(2015, now()->year);

            $chave = "{$tributo}-{$mes}-{$ano}";
        } while (in_array($chave, $combinacoesUsadas));

        // Marca como usada
        $combinacoesUsadas[] = $chave;

        return [
            'tributo' => $tributo,
            'mes' => $mes,
            'ano' => $ano,
            'valor' => fake()->randomFloat(2, 100, 100000),
        ];
    }
}
