<x-app-layout>
    <div class="fixed right-8 top-12 text-center border border-gray-300 rounded-md py-4 px-9 bg-white ">
        <h3>Duração total das ligações</h3>
        <h2 class="text-3xl">{{$totalizerCallTime}}</h2>
    </div>
    <div class="mt-40">
        <div class="grid grid-cols-6 gap-2 h-full px-8">
            <div class="col-span-5">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-4 border border-gray-300 rounded-md">
                    <div class="text-center px-4 py-12 text-gray-900 dark:text-gray-100">
                        <h3>Total de ligações</h3>
                        <div class="flex justify-center items-center mt-2">
                            <h2 class="w-8/12 shadow-md border border-gray-300 rounded-md text-7xl p-12">{{$total}}</h2>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-6">
                            <h3>Atendidas</h3>
                            <h3>Não atendidas</h3>
                            <h3>Agendadas</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-4 text-lg">
                            <div class="grid-span-1 bg-white shadow-md border border-gray-300 rounded-md py-9 px-4">
                                <div>
                                    <h2>{{ $callStatus['atendidas']->count() }}</h2>
                                </div>
                            </div>
                            <div class="bg-white shadow-md border border-gray-300 rounded-md py-11 px-4">
                                <div>
                                    <h2>{{ $callStatus['nao_atendidas']->count() }}</h2>
                                </div>
                            </div>
                            <div class="bg-white shadow-md border border-gray-300 rounded-md py-11 px-4">
                                <div>
                                    <h2>{{ $callStatus['agendadas']->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full border border-gray-300">
                    <div class="p-6 text-gray-900 dark:text-gray-100 h-full flex flex-col">
                        <h3 class="p-2 text-center">Ranking de Ligações</h3>
                        <ol class="list-decimal list-inside overflow-y-auto">
                            @php
                                $first = true;
                            @endphp
                            @foreach ($ranking as $item)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">
                                    <div class="flex flex-col">
                                        <span class="flex items-center justify-between {{ $first ? 'text-yellow-400' : '' }}">
                                            {{ $item->user_name }}
                                            @if ($first)
                                                @svg('emoji-rocket', 'w-6 h-6 ml-2')
                                            @endif
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ $item->total }} ligações</span>
                                    </div>
                                </div>
                                @php
                                    $first = false;
                                @endphp
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-2">
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const callsByMonthChartData = @json($callsByMonthChartData);
       
        // Chama a função para o Gráfico de Ligações por Mês
        createChart(
            callsByMonthChartData.data,
            callsByMonthChartData.labels,
            'callsByMonthChart'
        );
    });
</script>