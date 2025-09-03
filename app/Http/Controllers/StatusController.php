<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class StatusController extends Controller
{
    public function searchStatus(Request $request){
        $query = $request->input('q');
        // Se a busca estiver vazia, retorne uma resposta vazia para evitar a busca de todos os clientes
        $status = Status::where('descricao', 'like', "%{$query}%")->paginate(10);
        
        return response()->json($status);
    }
}
