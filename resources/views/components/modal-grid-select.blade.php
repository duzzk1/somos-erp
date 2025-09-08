<form @submit="if (!selectedItem) event.preventDefault();">
    <div x-data="{ 
        open: false, 
        selectedItem: null,
        loading: false,
        loadingMore: false,
        search: '',
        items: [],
        currentPage: 1,
        hasMore: true,
        apiUrl: '{{ $apiUrl }}',
        fetchItems(page = 1) {
            if (page === 1) {
                this.loading = true;
                this.items = [];
                this.hasMore = true;
            } else {
                this.loadingMore = true;
            }
    
            fetch(`${this.apiUrl}?q=${this.search}&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    this.items = [...this.items, ...data.data];
                    this.currentPage = data.current_page;
                    this.hasMore = data.current_page < data.last_page;
                }).finally(() => {
                    this.loading = false;
                    this.loadingMore = false;
                });
        },
        loadMore() {
            if (this.hasMore && !this.loading && !this.loadingMore) {
                this.fetchItems(this.currentPage + 1);
            }
        }
    }" x-init="fetchItems()" class="relative">
    
        <div class="flex items-center space-x-2">
            <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
        </div>
    
        <input type="hidden" name="{{ $name }}" x-model="selectedItem ? selectedItem.{{ $valueKey }} : ''">
        
        <div @click="open = true; fetchItems()" x-text="selectedItem ? selectedItem.{{ $displayKey }} : 'Nenhum item selecionado'" class="mt-1 p-2 border rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 text-gray-900 cursor-pointer"></div>
    
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition-all sm:my-8 sm:w-full sm:max-w-4xl z-50">
                    <div class="px-6 py-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $label }}</h3>
                    </div>
                    
                    <div class="px-6 py-4 max-h-96 overflow-y-auto" @scroll.debounce.100ms="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 50) loadMore()">
                        <input 
                            type="text" 
                            x-model="search" 
                            @input.debounce.500ms="currentPage = 1; fetchItems()" 
                            placeholder="Buscar..." 
                            class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 mb-4 sticky top-0 bg-white dark:bg-gray-800 z-10"
                        >
                        
                        <div x-show="loading" class="text-center py-8">
                            <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-900">Carregando dados...</p>
                        </div>
                        
                        <div x-show="!loading" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $displayKey }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="item in items" :key="item.{{ $valueKey }}">
                                        <tr @click="selectedItem = item; open = false" class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200" x-text="item.{{ $displayKey }}"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div x-show="loadingMore" class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Carregando mais...</p>
                            </div>
                            <div x-show="!hasMore && items.length > 0" class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Todos os itens foram carregados.</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right">
                        <button @click="open = false" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>