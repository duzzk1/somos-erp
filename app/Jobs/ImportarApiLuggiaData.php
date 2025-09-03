<?php

namespace App\Jobs;

use App\Models\Clientes;
use App\Models\Oportunidades;
use Date;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\VarDumper\VarDumper;


class ImportarApiLuggiaData implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;
    private $usuariosUrl = 'https://crm.luggia.com.br/api/v2/pessoas/all';
    private $authUrl = 'https://crm.luggia.com.br/oauth';
    private $loginUrl = 'https://crm.luggia.com.br/api/v3/pessoas/login';
    private $clientesUrl = 'https://crm.luggia.com.br/api/v3/clientes?since';
    private $apiUrl = 'https://crm.luggia.com.br/api/v3/oportunidades/';
    private $username = '';
    private $password = '';
    private $accessToken = false;
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    /**
     * O ID que identifica o job. O Laravel o usará para o bloqueio.
     * Use os dados que tornam o job único, como o nome de usuário da integração.
     */
    public function uniqueId(): string
    {
        // Uma string única, por exemplo, o nome de usuário da integração
        $uniqueId = 'luggia-import-' . $this->username;
        Log::info("Gerando uniqueId: " . $uniqueId);
        return $uniqueId;
    }

    /**
     * Define por quanto tempo o bloqueio será mantido.
     * Isso impede que o job fique permanentemente bloqueado em caso de falha.
     */
    public function uniqueFor(): int
    {
        // O bloqueio será mantido por 5 minutos (300 segundos).
        return 18000;
    }
    private function loginApi() {
        $loginResponse = Http::withToken($this->accessToken)
            ->post($this->loginUrl, [
                'conta' => '1',
                'usuario' =>  env('API_LOGIN_USERNAME'),
                'senha' => env('API_LOGIN_PASSWORD'),
            ]);

        if (!$loginResponse->successful()) {
            throw new Exception("Falha no login da API. Status: {$loginResponse->status()}");
        }
    }

    private function getApiToken() {
        $this->accessToken = Cache::get('luggia_access_token') ?? false;
        if (!$this->accessToken) {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->asForm()
                ->post($this->authUrl, ['grant_type' => 'client_credentials']);

            if (!$response->successful()) {
                throw new Exception("Falha na autenticação da API. Status: {$response->status()}");
            }

            $tokenData = $response->json();
            $this->accessToken = $tokenData['access_token'];
            $expiresIn = $tokenData['expires_in'];
            Cache::put('luggia_access_token', $this->accessToken, now()->addSeconds($expiresIn - 60));
        }
    }

    private function import() {
        // Importa os dados dos clientes
        $this->importClientData();
        // Importa os dados das oportunidades
        $this->importOportunidadesData();
    }

    public function handle()
    {
        try {
            // 1. Obtém o token do cache ou faz a requisição de autenticação
            $this->getApiToken();
            // 2. Faz login na API
            $this->loginApi();
            // Importa os dados
            $this->import();            
        } catch (Exception $e) {
            Log::error('Erro fatal no job de importação: ' . $e->getMessage());
            // Lança a exceção para que o job seja marcado como falho e possa ser retentado
            throw $e;
        }
    }
    private function importClientData(){
        Log::info(message: "Importação de clientes iniciada com sucesso.");
        $page = 1;
        $allDataImported = false;

        while (!$allDataImported) {
            $response = Http::withToken($this->accessToken)->get($this->clientesUrl, [
                'page' => $page,
                'limit' => 500,
            ]);

            if (!$response->successful()) {
                throw new Exception("Erro ao buscar dados de clientes da página {$page}. Status: {$response->status()}");
            }
            
            $clientsData = $response->json();

            if (!isset($clientsData['data']) || empty($clientsData['data'])) {
                $allDataImported = true;
                continue;
            }

            // 3. Itera sobre os dados e usa updateOrCreate
            foreach ($clientsData['data'] as $data) {
                if (!isset($data['guid'])) {
                    Log::warning("Cliente sem GUID. Pulando registro.");
                    continue;
                }

                $data['dataNascimento'] = \Carbon\Carbon::parse($data['dataNascimento']);

                Clientes::updateOrCreate(
                    ['guid' => $data['guid']],
                    [
                        'agencia' => $data['agencia'] ?? null,
                        'apelidoFantasia' => $data['apelidoFantasia'] ?? null,
                        'bairro' => $data['bairro'] ?? null,
                        'banco' => $data['banco'] ?? null,
                        'celular' => $data['celular'] ?? null,
                        'cep' => $data['cep'] ?? null,
                        'codigo' => $data['codigo'] ?? null,
                        'complemento' => $data['complemento'] ?? null,
                        'consumidorFinal' => $data['consumidorFinal'] ?? null,
                        'contaCorrente' => $data['contaCorrente'] ?? null,
                        'cpfcnpj' => $data['cpfcnpj'] ?? null,
                        'dataAtualizacao' => $data['dataAtualizacao'] ?? null,
                        'dataNascimento' => $data['dataNascimento'] ?? null,
                        'email' => $data['email'] ?? null,
                        'emailAdicional' => $data['emailAdicional'] ?? null,
                        'endereco' => $data['endereco'] ?? null,
                        'enderecoAdicional' => $data['enderecoAdicional'] ?? null,
                        'inscricaoEstadual' => $data['inscricaoEstadual'] ?? null,
                        'latitude' => $data['latitude'] ?? null,
                        'longitude' => $data['longitude'] ?? null,
                        'municipio' => $data['municipio'] ?? null,
                        'municipioIbge' => $data['municipioIbge'] ?? null,
                        'nome' => $data['nome'] ?? null,
                        'numero' => $data['numero'] ?? null,
                        'numeroBanco' => $data['numeroBanco'] ?? null,
                        'observacoes' => $data['observacoes'] ?? null,
                        'origem' => $data['origem'] ?? null,
                        'pagamentoCondicao' => $data['pagamentoCondicao'] ?? null,
                        'pagamentoForma' => $data['pagamentoForma'] ?? null,
                        'pagamentoTipo' => $data['pagamentoTipo'] ?? null,
                        'pais' => $data['pais'] ?? null,
                        'pessoa' => $data['pessoa'] ?? null,
                        'ramo' => $data['ramo'] ?? null,
                        'regimeTributario' => $data['regimeTributario'] ?? null,
                        'site' => $data['site'] ?? null,
                        'situacao' => $data['situacao'] ?? null,
                        'status' => $data['status'] ?? null,
                        'telefone' => $data['telefone'] ?? null,
                        'tipo' => $data['tipo'] ?? null,
                        'tipoContribuinte' => $data['tipoContribuinte'] ?? null,
                        'uf' => $data['uf'] ?? null,
                    ]
                );
            }
            $page++;
        }
        
        Log::info("Importação de clientes concluída com sucesso.");
    }
    public function importOportunidadesData(): void {
        Log::info(message: "Importação de oportunidades iniciada com sucesso.");

        $page = 1;
        $allDataImported = false;

        while (!$allDataImported) {
            $response = Http::withToken($this->accessToken)->get($this->apiUrl, [
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
                    $response = Http::withToken($this->accessToken)->get($this->usuariosUrl);
                
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
        
        Log::info(message: "Importação de oportunidades concluída com sucesso.");
    }
}