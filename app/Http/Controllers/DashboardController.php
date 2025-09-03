<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function filter(Request $request)
    {
        // Salva as datas na sessão
        session()->put('filter_start_date', $request->input('start_date'));
        session()->put('filter_end_date', $request->input('end_date'));
        
        // Redireciona para a URL limpa da dashboard
        return redirect()->route('dashboard.calls');
    }
    public function calls(Request $request)
    {
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        // Cria a query base
        $callsQuery = Calls::query();

        // Aplica o filtro de data se as datas existirem no request
         if ($startDate && $endDate) {
            // Se ambas as datas existirem, usa whereBetween
            $callsQuery->whereBetween('call_date', [
                Carbon::parse($startDate),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } elseif ($startDate) {
            // Se apenas a data de início existir, usa whereDate
            $callsQuery->whereDate('call_date', '>=', Carbon::parse($startDate));
        } elseif ($endDate) {
            // Se apenas a data de fim existir, usa whereDate
            $callsQuery->whereDate('call_date', '<=', Carbon::parse($endDate));
        }

        // --- Use a $callsQuery filtrada para todas as suas consultas ---
        $total = (clone $callsQuery)->count();

        $ranking = (clone $callsQuery)
            ->select(
                DB::raw('count(*) as total'), 
                DB::raw('CASE WHEN direction = "Outbound" THEN `from` ELSE `to` END AS user_name')
            )
            ->groupBy('user_name')
            ->orderByDesc('total')
            ->get();
        
        $totalizerCallTime = (clone $callsQuery)
            ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(call_time))) as total_duration'))
            ->get();
        
        $callStatus = [
            'atendidas' => (clone $callsQuery)->where('status', 'Answered')->get(),
            'nao_atendidas' => (clone $callsQuery)->where('status', 'Unanswered')->get(),
            'agendadas' => (clone $callsQuery)->where('status', 'Scheduled')->get(),
        ];
        
        return view('dashboards.calls', [
            'total' => $total,
            'ranking' => $ranking,
            'callStatus' => $callStatus,
            'totalizerCallTime' => $totalizerCallTime->pluck('total_duration')[0],
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}