<?php

namespace App\Jobs;

use App\Models\Oportunidades;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\VarDumper\VarDumper;

class ImportarOportunidadesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public static function handle(): void
    {
        $authUrl = 'https://crm.luggia.com.br/oauth';
        $loginUrl = 'https://crm.luggia.com.br/api/v3/pessoas/login';
        $usuarioGet = 'https://crm.luggia.com.br/api/v2/pessoas/all';
        $apiUrl = 'https://crm.luggia.com.br/api/v3/oportunidades/';

        $username = env('API_USERNAME');
        $password = env('API_PASSWORD');
        $loginUsername = env('API_LOGIN_USERNAME');
        $loginPassword = env('API_LOGIN_PASSWORD');
        try {
            // 1. Obtém o token do cache ou faz a requisição de autenticação
            $accessToken = Cache::get('luggia_access_token');
            if (!$accessToken) {
                $response = Http::withBasicAuth($username, $password)
                    ->asForm()
                    ->post($authUrl, ['grant_type' => 'client_credentials']);
                
                if (!$response->successful()) {
                    throw new Exception("Falha na autenticação da API. Status: {$response->status()}");
                }
                
                $tokenData = $response->json();
                $accessToken = $tokenData['access_token'];
                $expiresIn = $tokenData['expires_in'];
                Cache::put('luggia_access_token', $accessToken, now()->addSeconds($expiresIn - 60));
            }

            // 2. Faz login na API
            $loginResponse = Http::withToken($accessToken)
                ->post($loginUrl, [
                    'conta' => '1',
                    'usuario' => $loginUsername,
                    'senha' => $loginPassword,
                ]);

            if (!$loginResponse->successful()) {
                throw new Exception("Falha no login da API. Status: {$loginResponse->status()}");
            }

            // 3. Itera sobre as páginas para buscar os dados
            $page = 1;
            $allDataImported = false;

            while (!$allDataImported) {
                $response = Http::withToken($accessToken)->get($apiUrl, [
                    'page' => $page,
                    'limit' => 500,
                ]);
                if (!$response->successful()) {
                    throw new Exception("Erro de conexão ao buscar dados da página {$page}. Status: {$response->status()}");
                }

                $oportunidadesData = $response->json();
                if (!isset($oportunidadesData['data']) || empty($oportunidadesData['data'])) {
                    $allDataImported = true;
                    continue;
                }
                
                // 4. Salva ou atualiza os dados no banco de dados
                foreach ($oportunidadesData['data'] as $data) {
                    if (!isset($data['guid'])) {
                        Log::warning("Oportunidade sem GUID. Pulando registro.");
                        continue;
                    }
                            

                    if (isset($data['responsavel'])){
                        $response = Http::withToken($accessToken)->get($usuarioGet);
                    
                        if ($response->successful()) {
                            $responsaveisData = $response->body();
                            $jsonObjects = explode("\n", $responsaveisData);
                            foreach ($jsonObjects as $jsonString) {
                                // Remove espaços em branco
                                $jsonString = trim($jsonString);

                                if (!empty($jsonString)) {
                                    $dataNew = json_decode($jsonString,true);
                                    // Verifique se a decodificação foi bem-sucedida
                                    if ($dataNew !== null) {
                                        $responsaveisDataNew[] = $dataNew;
                                    }
                                }
                            }
                            foreach ($responsaveisDataNew as $responsavel) {
                                if ($responsavel['id'] == $data['responsavel']) {
                                    $data['responsavel'] = $responsavel['nome'] ;
                                    break;
                                }
                            }
                        }
                    }
                    Oportunidades::updateOrCreate(
                        ['guid' => $data['guid']],
                        [
                            'cliente' => $data['cliente'] ?? null,
                            'clienteCodigo' => $data['clienteCodigo'] ?? null,
                            'codigo' => $data['codigo'] ?? null,
                            'complemento' => $data['complemento'] ?? null,
                            'contato' => $data['contato'] ?? null,
                            'dataAtualizacao' => $data['dataAtualizacao'] ?? null,
                            'dataCriacao' => $data['dataCriacao'] ?? null,
                            'dataPrevista' => $data['dataPrevista'] ?? null,
                            'descricao' => $data['descricao'] ?? null,
                            'extra' => isset($data['extra']) ? json_encode($data['extra']) : null,
                            'filial' => $data['filial'] ?? null,
                            'funil' => $data['funil'] ?? null,
                            'latitude' => $data['latitude'] ?? null,
                            'local' => $data['local'] ?? null,
                            'localEvento' => $data['localEvento'] ?? null,
                            'longitude' => $data['longitude'] ?? null,
                            'motivo' => $data['motivo'] ?? null,
                            'numeroProjeto' => $data['numeroProjeto'] ?? null,
                            'observacoesEstrategia' => $data['observacoesEstrategia'] ?? null,
                            'orcamento' => $data['orcamento'] ?? null,
                            'origem' => $data['origem'] ?? null,
                            'probabilidade' => $data['probabilidade'] ?? null,
                            'responsaveis' => isset($data['responsaveis']) ? json_encode($data['responsaveis']) : null,
                            'responsavel' => $data['responsavel'] ?? null,
                            'status' => $data['status'] ?? null,
                            'valorTotal' => $data['valorTotal'] ?? null,
                        ]
                    );
                }
                $page++;
            }

            Log::info("Importação de oportunidades concluída com sucesso.");
        } catch (Exception $e) {
            Log::error('Erro fatal no job de importação: ' . $e->getMessage());
            // Lança a exceção para que o job seja marcado como falho e possa ser retentado
            throw $e;
        }
    }
}