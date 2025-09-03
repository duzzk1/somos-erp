<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use App\Models\Oportunidades;
use Symfony\Component\VarDumper\VarDumper;

class OportunidadesController extends Controller
{

    public function index(Request $request)
    {           
        $query = Oportunidades::orderBy('dataCriacao', 'desc')->join('clients', 'oportunidades.cliente', '=', 'clients.guid')
            ->select('oportunidades.*', 'clients.nome', 'clients.cpfcnpj', 'clients.email');  
        // Lógica de busca
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where('cliente', 'like', '%' . $searchTerm . '%')->join('clientes', 'oportunidades.cliente', '=', 'clientes.guid')
                  ->orWhere('contato', 'like', '%' . $searchTerm . '%');
        }

        // Lógica de filtro por status
        if ($request->filled('statusFilter')) {
            $query->where('status', $request->get('statusFilter'));
        }

        // Lógica de filtro por responsáveis
        if ($request->filled('responsavelFilter')) {
            // Se o campo responsavel é um array (JSON), a consulta é um pouco diferente
            $responsavel = $request->get('responsavelFilter');
            $query->where('responsavel', $responsavel);
        }
        $oportunidades = $query->paginate(15);
        // Passa os dados únicos para popular os filtros
        
        $statuses = json_encode(Oportunidades::distinct()->pluck('oportunidades.status'));

        $responsavel = json_encode(Oportunidades::distinct()->pluck('responsavel'));

        return view('grids.oportunidades-grid', compact('oportunidades', 'statuses', 'responsavel'));

    }
    /**
     * Atualiza uma oportunidade no banco de dados.
     */
    public function update(Request $request, Oportunidades $oportunidade)
    {
            
        // Validação básica dos dados do formulário
        $validated = $request->validate([
            'clienteCodigo' => 'string|max:255',
            'contato' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'observacoesEstrategia' => 'nullable|string',
            'valorTotal' => 'nullable|numeric',
            'responsavel' => 'nullable|string',
        ]);
        // Converte a string de responsáveis para um array,
        // garantindo que ele seja armazenado como JSON
        $oportunidade->clienteCodigo = $validated['clienteCodigo'] ?? '';
        $oportunidade->contato = $validated['contato'] ?? '';
        $oportunidade->status = $validated['status'] ?? '';
        $oportunidade->observacoesEstrategia = $validated['observacoesEstrategia'] ?? '';
        $oportunidade->valorTotal = $validated['valorTotal'] ?? '';
        $oportunidade->responsavel = $validated['responsavel'] ?? '';

        $oportunidade->save();

        return redirect()->route('oportunidades.index')->with('success', 'Oportunidade atualizada com sucesso!');
    }

    public function create(Request $request)
    {
     
        // Validação básica dos dados do formulário
        $validated = $request->validate([
            'cliente_id' => 'string|max:255',
            'status' => 'required|string|max:255',
            'observacoesEstrategia' => 'nullable|string',
            'valorTotal' => 'nullable|numeric',
            'responsavel' => 'nullable|string',
        ]);
        
        $clientData = Clientes::where('guid', $validated['cliente_id'])->first(['nome', 'cpfcnpj', 'email', 'telefone', 'celular']);

        $oportunidade = new Oportunidades();
        $oportunidade->guid = \Str::uuid();
        $oportunidade->cliente = $validated['cliente_id'] ?? '';
        $oportunidade->clienteCodigo = $clientData->cpfcnpj ?? '';
        $oportunidade->contato = $clientData->celular ?? $clientData->telefone ?? $clientData->email ?? '';
        $oportunidade->status = $validated['status'] ?? '';
        $oportunidade->observacoesEstrategia = $validated['observacoesEstrategia'] ?? '';
        $oportunidade->valorTotal = $validated['valorTotal'];
        $oportunidade->responsavel = $validated['responsavel'] ?? '';
        $oportunidade->dataCriacao = now();

        $oportunidade->save();
        
        return redirect()->route('oportunidades.index')->with('success', 'Oportunidade criada com sucesso!');
    }
}