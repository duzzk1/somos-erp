<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OportunidadesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guid' => Str::uuid(),
            'cliente' => $this->faker->company,
            'clienteCodigo' => $this->faker->numerify('CLT###'),
            'codigo' => $this->faker->numberBetween(1000, 9999),
            'complemento' => $this->faker->streetSuffix,
            'contato' => $this->faker->name,
            'dataAtualizacao' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'dataCriacao' => $this->faker->dateTimeBetween('-2 months', '-1 month'),
            'dataPrevista' => $this->faker->dateTimeBetween('now', '+2 months'),
            'descricao' => $this->faker->sentence,
            'extra' => json_encode(['info' => $this->faker->words(3, true)]),
            'filial' => $this->faker->city,
            'funil' => Str::uuid(),
            'latitude' => $this->faker->latitude,
            'local' => $this->faker->city,
            'localEvento' => $this->faker->city,
            'longitude' => $this->faker->longitude,
            'motivo' => $this->faker->word,
            'numeroProjeto' => $this->faker->numerify('PRJ####'),
            'observacoesEstrategia' => $this->faker->paragraph,
            'orcamento' => $this->faker->numerify('R$ ####,##'),
            'origem' => $this->faker->randomElement(['Indicação', 'Site', 'Telefone', 'Email']),
            'probabilidade' => $this->faker->randomElement(['Alta', 'Média', 'Baixa']),
            'responsaveis' => json_encode([$this->faker->name, $this->faker->name]),
            'responsavel' => $this->faker->name,
            'status' => $this->faker->randomElement(['Aberta', 'Fechada', 'Em andamento']),
            'valorTotal' => $this->faker->randomFloat(2, 1000, 50000),
        ];
    }
}