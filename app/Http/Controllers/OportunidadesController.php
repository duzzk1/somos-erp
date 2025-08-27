<?php

namespace App\Http\Controllers;

use App\Jobs\ImportarOportunidadesJob;
use Illuminate\Http\Request;
use App\Models\Oportunidades;
use Symfony\Component\VarDumper\VarDumper;

class OportunidadesController extends Controller
{
    public function index(Request $request)
    {
        
        ImportarOportunidadesJob::dispatch();     
           
        $query = Oportunidades::orderBy('dataCriacao', 'desc');

        // Lógica de busca
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where('cliente', 'like', '%' . $searchTerm . '%')
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
        
        $statuses = json_encode(Oportunidades::distinct()->pluck('status'));

        $responsavel = json_encode(Oportunidades::distinct()->pluck('responsavel'));

        return view('components.oportunidades-grid', compact('oportunidades', 'statuses', 'responsavel'));

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
        $oportunidade->clienteCodigo = $validated['clienteCodigo'];
        $oportunidade->contato = $validated['contato'];
        $oportunidade->status = $validated['status'];
        $oportunidade->observacoesEstrategia = $validated['observacoesEstrategia'];
        $oportunidade->valorTotal = $validated['valorTotal'];
        $oportunidade->responsavel = $validated['responsavel'];

        $oportunidade->save();

        return redirect()->route('oportunidades.index')->with('success', 'Oportunidade atualizada com sucesso!');
    }
}