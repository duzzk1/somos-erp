<div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div @click="openEditModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all sm:my-8 sm:w-full sm:max-w-xl z-50">
            <div class="px-6 py-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    Editar Oportunidade
                </h3>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('oportunidades.update', $oportunidade->guid ?? '') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="clienteCodigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CNPJ</label>
                            <input type="text" name="clienteCodigo" id="clienteCodigo" value="{{ old('clienteCodigo', $oportunidade->clienteCodigo ?? '') }}" maxlength="255" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="contato" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contato</label>
                            <input type="text" name="contato" id="contato" value="{{ old('contato', $oportunidade->contato ?? '') }}" maxlength="255" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <input type="text" name="status" id="status" value="{{ old('status', $oportunidade->status ?? '') }}" required maxlength="255" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="observacoesEstrategia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                            <textarea name="observacoesEstrategia" id="observacoesEstrategia" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">{{ old('observacoesEstrategia', $oportunidade->observacoesEstrategia ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="valorTotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor Total</label>
                            <input type="number" step="0.01" name="valorTotal" id="valorTotal" value="{{ old('valorTotal', $oportunidade->valorTotal ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="responsavel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsável</label>
                            <input type="text" name="responsavel" id="responsavel" value="{{ old('responsavel', $oportunidade->responsavel ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="openEditModal = false" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
