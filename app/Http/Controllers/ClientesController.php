<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class ClientesController extends Controller
{
    public function index(Request $request)
    {           
        $query = Clientes::orderBy('id', 'desc');

        // Lógica de busca
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where('nome', 'like', '%' . $searchTerm . '%')
                  ->orWhere('telefone', 'like', '%' . $searchTerm . '%');
        }

        $clientes = $query->paginate(15);

        return view('grids.clientes-grid', compact('clientes'));

    }

    public function searchClient(Request $request){
        $query = $request->input('q');
        
        // Se a busca estiver vazia, retorne uma resposta vazia para evitar a busca de todos os clientes
        if (!$query) {
            return response()->json([]);
        }

        $clientes = Clientes::where('nome', 'like', "%{$query}%")
            ->orWhere('cpfcnpj', 'like', "%{$query}%")
            ->get(['id', 'nome', 'cpfcnpj', 'email', 'telefone', 'celular', 'guid']); // Inclua os campos necessários
        // Mapeie os resultados para o formato esperado pelo Tom Select (label, value, etc.)
        $formattedClients = $clientes->map(function ($cliente) {
            return [
                'value' => $cliente->id,
                'label' => "{$cliente->nome} - {$cliente->cpfcnpj}",
                'cliente' => $cliente->guid,
                'cpfcnpj' => $cliente->cpfcnpj,
                'contato' => $cliente->celular ?? $cliente->telefone, // Lógica para o contato
            ];
        });

        return response()->json($formattedClients);
    }

    public function searchModal(Request $request)
    {

        $query = $request->input('q');
        
        $clientes = Clientes::where('nome', 'like', "%{$query}%")
                      ->orWhere('cpfcnpj', 'like', "%{$query}%")
                      ->orWhere('celular', 'like', "%{$query}%")
                        ->orderBy('nome', 'asc')
                      ->select('guid', 'nome', 'cpfcnpj', 'celular')
                      ->paginate(10);

        return response()->json($clientes);
    }
}

