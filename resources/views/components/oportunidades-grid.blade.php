<x-app-layout>
    <div x-data="{ open: false }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="mt-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Oportunidades
                        </h1>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('oportunidades.index') }}" method="GET">
                            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4 mb-6">
                                <div class="w-full md:w-1/3">
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Buscar por cliente ou contato..."
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    />
                                </div>
                                <div class="w-full md:w-1/4">
                                    <select
                                        name="statusFilter"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    >
                                        <option value="">Filtrar por Status</option>
                                        @php
                                            if(!isset($statuses)) {
                                                $statuses = '[]';
                                            }
                                            $statuses = json_decode($statuses) ? json_decode($statuses) : [];
                                        @endphp
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" @selected(request('statusFilter') == $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-1/4">
                                    <select
                                        name="responsavelFilter"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    >
                                        <option value="">Filtrar por Responsável</option>
                                        @php
                                            if(!isset($responsavel)) {
                                                $responsavel = '[]';
                                            }
                                            $responsavel = json_decode($responsavel);
                                        @endphp
                                        @foreach($responsavel as $user)
                                            <option value="{{ $user }}" @selected(request('responsavelFilter') == $user)>{{ $user }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Aplicar
                                </button>
                            </div>
                        </form>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cadastro</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CNPJ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">E-mail</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Responsáveis</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @if(!isset($oportunidades) || $oportunidades->isEmpty())
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                Nenhuma oportunidade encontrada.
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($oportunidades as $oportunidade)
                                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $oportunidade->dataCriacao->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $oportunidade->clienteCodigo ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $oportunidade->contato ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $oportunidade->email ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $oportunidade->status ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $oportunidade->responsavel ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="#" @click="open = true">Editar</a>
                                                    <a href="#" class="text-red-600 hover:text-red-900 ml-4">Excluir</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            @if(isset($oportunidades))
                                {{ $oportunidades->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Alpine.js -->
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                {{-- clienteCodigo --}}
                <h2 class="text-lg font-bold mb-4">Editar Oportunidade</h2>
                    @include('oportunidades.edit-modal')
                <button @click="open = false" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded">Fechar</button>
            </div>
        </div>
    </div>
</x-app-layout>