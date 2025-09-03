<x-app-layout>
    <div class="w-full px-2 md:px-8 py-6">
        <form action="{{ route('dashboard.calls.filter') }}" method="POST" class="flex flex-col md:flex-row items-center gap-4 mb-8 w-full max-w-4xl mx-auto">
            @csrf
            <div class="flex flex-col w-full md:w-auto">
                <label for="start_date" class="text-sm font-medium text-gray-700">Data de Início</label>
                <input type="date" id="start_date" name="start_date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    value="{{ old('start_date', request('start_date', $start_date ?? '')) }}">
            </div>
            <div class="flex flex-col w-full md:w-auto">
                <label for="end_date" class="text-sm font-medium text-gray-700">Data de Fim</label>
                <input type="date" id="end_date" name="end_date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    value="{{ old('end_date', request('end_date', $end_date ?? '')) }}">
            </div>
            <button type="submit"
                class="mt-4 md:mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                Filtrar
            </button>
        </form>

        <div class="w-full flex flex-col items-center mb-8 max-w-4xl mx-auto">
            <div class="w-full bg-white border border-gray-300 rounded-md py-4 px-6 shadow text-center">
                <h3 class="mb-2">Duração total das ligações</h3>
                <h2 class="text-3xl">{{$totalizerCallTime}}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-6 gap-6 w-full max-w-4xl mx-auto">
            <div class="col-span-1 lg:col-span-4 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-md mb-4 border border-gray-300 w-full">
                    <div class="text-center px-4 py-12 text-gray-900 dark:text-gray-100">
                        <h3>Total de ligações</h3>
                        <div class="flex justify-center items-center mt-2">
                            <h2 class="w-full max-w-xs shadow-md border border-gray-300 rounded-md text-5xl md:text-7xl p-8 md:p-12">{{$total}}</h2>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-6">
                            <h3>Atendidas</h3>
                            <h3>Não atendidas</h3>
                            <h3>Agendadas</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-4 text-lg">
                            <div class="bg-white shadow-md border border-gray-300 rounded-md py-6 px-2 md:py-9 md:px-4">
                                <h2>{{ $callStatus['atendidas']->count() }}</h2>
                            </div>
                            <div class="bg-white shadow-md border border-gray-300 rounded-md py-6 px-2 md:py-11 md:px-4">
                                <h2>{{ $callStatus['nao_atendidas']->count() }}</h2>
                            </div>
                            <div class="bg-white shadow-md border border-gray-300 rounded-md py-6 px-2 md:py-11 md:px-4">
                                <h2>{{ $callStatus['agendadas']->count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1 lg:col-span-2 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-md border border-gray-300 h-full flex flex-col w-full max-w-xs">
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col h-full">
                        <h3 class="p-2 text-center">Ranking de Ligações</h3>
                        <ol class="list-decimal list-inside overflow-y-auto break-words" style="max-height: 500px;">
                            @php $first = true; @endphp
                            @foreach ($ranking as $item)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">
                                    <div class="flex flex-col">
                                        <span class="flex items-center justify-between {{ $first ? 'text-yellow-400' : '' }}">
                                            <span class="break-words">{{ $item->user_name }}</span>
                                            @if ($first)
                                                @svg('emoji-rocket', 'w-6 h-6 ml-2')
                                            @endif
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ $item->total }} ligações</span>
                                    </div>
                                </div>
                                @php $first = false; @endphp
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>