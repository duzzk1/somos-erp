<div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div @click="openCreateModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all sm:my-8 sm:w-full sm:max-w-xl z-50">
            <div class="px-6 py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    Criar Oportunidade
                </h3>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('oportunidades.create') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-modal-grid-select
                                name="cliente_id"
                                label="Cliente"
                                api-url="{{ route('clientes.searchModal') }}"
                                display-key="nome"
                                value-key="guid"
                            />
                        </div>
                        <div>
                            <x-modal-grid-select
                                class="required"
                                name="status"
                                label="Status"
                                api-url="{{ route('status.searchStatus') }}"
                                display-key="descricao"
                                value-key="descricao"
                            />
                        </div>
                        <div>
                            <label for="observacoesEstrategia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                            <textarea name="observacoesEstrategia" id="observacoesEstrategia" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                        </div>
                        <div>
                            <label for="valorTotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Total</label>
                            <input type="number" step="0.01" name="valorTotal" id="valorTotal" value="" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <x-modal-grid-select
                                name="responsavel"
                                label="Responsável"
                                api-url="{{ route('user.searchStatus') }}"
                                display-key="name"
                                value-key="name"
                            />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="openCreateModal = false" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>