<?php

namespace Database\Seeders;

use App\Models\Integracoes;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Oportunidades;
use App\Models\Status;
use App\Models\User;
use Crypt;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Eduardo',
            'email' => 'edu@adm',
            'password' => Hash::make('123'),
        ]);
        User::factory()->create([
            'name' => 'Larissa',
            'email' => 'larissa@adm',
            'password' => Hash::make('123'),
        ]);
        Integracoes::factory()->create([
            'nome' => 'CallsCsv',
            'descricao' => 'Busca dados de chamadas via CSV',
            'status' => 'ativa',
        ]);

         Integracoes::factory()->create([
            'nome' => 'Luggia',
            'descricao' => 'Busca dados via API Luggia',
            'status' => 'ativa',
            'user' => env('API_LUGGIA_USERNAME'),
            'password' => Crypt::encryptString(env('API_LUGGIA_PASSWORD')),
        ]);

        Oportunidades::factory(1)->create();
    }
}
