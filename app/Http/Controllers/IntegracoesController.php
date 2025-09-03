<?php

namespace App\Http\Controllers;

use App\Jobs\ImportarApiLuggiaData;
use App\Jobs\ImportarChamadasCSV;
use App\Models\Integracoes;
use Crypt;
use Illuminate\Http\Request;

class IntegracoesController extends Controller
{
    public function index() {
        $query = Integracoes::orderBy('nome', 'desc');
        $integracoes = $query->paginate(15);

        return view('grids.integracoes-grid', compact('integracoes'));
    }

    public function search(Request $request, int $integrationId) {
    
         try {
            $integracao = Integracoes::findOrFail($integrationId);
            
            // Decifrar a senha da integração
            $usuario = $integracao->user;
            $password = Crypt::decryptString($integracao->password);

            if ($integracao->nome == 'Luggia') {
                ImportarApiLuggiaData::dispatch($usuario, $password);
            } elseif ($integracao->nome == 'CallsCsv') {
                ImportarChamadasCSV::handle();
            }

            // Redirecionar com mensagem de sucesso
            $message = 'Busca de dados em segundo plano iniciada com sucesso! Por favor, aguarde alguns minutos.';
            return redirect()->route('integracoes.index')->with('success', $message);
            
        } catch (\Exception $e) {
            // Se algo der errado, redirecionar com mensagem de erro
            $message = 'Ocorreu um erro ao iniciar a busca: ' . $e->getMessage();
            return redirect()->route('integracoes.index')->with('error', $message);
        }
    }
}
