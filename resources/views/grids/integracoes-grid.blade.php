<x-app-layout>
    <x-popup-message />
    <div x-data="{ open: false }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="mt-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Integrações
                        </h1>
                    </div>

                    <div class="p-6">     
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @if(!isset($integracoes) || $integracoes->isEmpty())
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                Nenhuma integração cadastrada entre em contato com o suporte para solicitar uma nova integração.
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($integracoes as $integracao)
                                            <tr class="">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $integracao->nome}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $integracao->descricao}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('integracoes.search', $integracao->id) }}" method="post" x-data="{ isLoading: false }" @submit="isLoading = true">
                                                        @csrf
                                                        <a href="#" @click="open = true" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-400" x-show="!isLoading">Editar</a>
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-400" 
                                                                :disabled="isLoading">
                                                            <span x-show="!isLoading">
                                                                Buscar
                                                            </span>
                                                            <span x-show="isLoading" class="flex items-center">
                                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                Buscando...
                                                            </span>
                                                        </button>
                                                    </form>
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
    </div>
</x-app-layout>