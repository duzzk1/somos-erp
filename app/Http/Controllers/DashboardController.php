<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use DB;

class DashboardController extends Controller
{
     public function index()
    {
        // Consulta para o gráfico de Ligações por Pessoa
        $callsTotalizer = Calls::query()
             ->select(
                DB::raw('count(*) as total'), 
            )
            ->orderByDesc('total')
            ->get();
        
        $ranking = Calls::query()
            ->select(
                DB::raw('count(*) as total'), 
                DB::raw('CASE WHEN direction = "Outbound" THEN `from` ELSE `to` END AS user_name')
            )
            ->groupBy('user_name')
            ->orderByDesc('total')
            ->get();
            
        // ---

        // Consulta para o gráfico de Ligações por Mês
      $callsByMonth = Calls::query()
            ->select(
                DB::raw('count(*) as total'), 
                DB::raw('DATE_FORMAT(call_date, "%M") as month')
            )
            ->groupBy(DB::raw('MONTH(call_date)'), DB::raw('DATE_FORMAT(call_date, "%M")'))
            ->orderBy(DB::raw('MONTH(call_date)'))
            ->get();
        
        $callsByMonthChartData = [
            'labels' => $callsByMonth->pluck('month'),
            'data' => $callsByMonth->pluck('total'),
        ];
        $totalizerCallTime = Calls::query()
            ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(call_time))) as total_duration'))
            ->get();
        
        $callStatus = [
            'atendidas' => Calls::where('status', 'Answered')->get(),
            'nao_atendidas' => Calls::where('status', 'Unanswered')->get(),
            'agendadas' => Calls::where('status', 'Scheduled')->get(),
        ];

        // Retorna todos os dados para a view
        return view('dashboard', data: ['total' => $callsTotalizer->pluck('total')[0], 'callsByMonthChartData' => $callsByMonthChartData, 'ranking' => $ranking, 'totalizerCallTime' => $totalizerCallTime->pluck('total_duration')[0], 'callStatus' => $callStatus]);
    }
}