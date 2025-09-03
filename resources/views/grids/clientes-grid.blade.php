<x-app-layout>
    <div x-data="{ openEditModal: false,  openCreateModal: false }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="mt-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Clientes
                        </h1>
                        <a href="#" @click="openCreateModal = true" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"">Adicionar</a>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('clientes.index') }}" method="GET">
                            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4 mb-6">
                                <div class="w-full md:w-1/3">
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Buscar por nome ou telefone..."
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                    />
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CNPJ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">E-mail</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @if(!isset($clientes) || $clientes->isEmpty())
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                Nenhum cliente encontrada.
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($clientes as $cliente)
                                            <tr title="{{ $cliente->nome ?? 'N/A' }}" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap overflow-hidden text-ellipsis text-sm text-gray-900 dark:text-gray-200">
                                                    {{ Str::limit($cliente->nome ?? 'N/A', 20) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $cliente->cpfcnpj ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $cliente->celular ?? 'N/A' }}
                                                </td>
                                                <td title="{{ $cliente->email ?? 'N/A' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                    {{ Str::limit($cliente->email ?? 'N/A', 32) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="#" @click="openEditModal = true">Editar</a>
                                                    <a href="#" class="text-red-600 hover:text-red-900 ml-4">Excluir</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            @if(isset($clientes))
                                {{ $clientes->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Modal Alpine.js create -->
        <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h2 class="text-lg font-bold mb-4">Adicionar cliente</h2>
                    @include('components.modals.clientes.create')
                <button @click="openCreateModal = false" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded">Fechar</button>
            </div>
        </div>
        <!-- Modal Alpine.js Edit -->
        <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h2 class="text-lg font-bold mb-4">Editar Cliente</h2>
                    @include('components.modals.clientes.edit')
                <button @click="openEditModal = false" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded">Fechar</button>
            </div>
        </div> --}}
    </div>
</x-app-layout>