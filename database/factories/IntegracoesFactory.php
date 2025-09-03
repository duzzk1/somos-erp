<?php

namespace Database\Factories;

use Crypt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class IntegracoesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' =>  fake()->name(),
            'descricao' =>  fake()->text(),
            'status' => 'ativa',
            'user' => fake()->userName(),
            'password' => Crypt::encryptString('password'),
        ];
    }
}
