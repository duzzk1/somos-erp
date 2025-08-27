<nav x-data="{ open: true }" class="flex dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="top-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg" :class="{ 'hidden': !open }">
        {{-- USER INFO --}}
        <div class="flex items-center space-x-4 p-2 mb-4">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ Auth::user()->id }}</div>
            </div>
        </div>

        <hr class="border-t border-gray-200 dark:border-gray-700 my-4">

        {{-- MENU ITENS --}}
        <nav class="w-full mt-2 flex flex-col items-center justify-center">
            <a href="/dashboard" class="text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:bg-gray-700 hover:rounded-lg transition duration-150 ease-in-out">
                Dashboard
            </a>
            <a href="/oportunidades" class="text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:bg-gray-700 hover:rounded-lg transition duration-150 ease-in-out mt-1">
                Oportunidades
            </a>
        </nav>
    </div>
      {{-- Botão para expandir/fechar o menu --}}
    <button @click="open = !open" class="absolute top-4 text-blue-500" :class="open ? 'left-56' : 'left-2'">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 6h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2zm0 4h14a1 1 0 110 2H3a1 1 0 110-2z"></path>
        </svg>
    </button>
</nav>
