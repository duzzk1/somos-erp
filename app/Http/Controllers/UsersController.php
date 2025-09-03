<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\VarDumper\VarDumper;

class UsersController extends Controller
{
    public function searchUsers(Request $request) {
        $query = $request->input('q');
        // Se a busca estiver vazia, retorne uma resposta vazia para evitar a busca de todos os clientes
        $user = User::where('name', 'like', "%{$query}%")->paginate(10);
        return response()->json($user);
    }
}
