<aside x-data="{ sidebarOpen: window.innerWidth >= 768 }"
     x-init="window.addEventListener('resize', () => { sidebarOpen = window.innerWidth >= 768 })"
     :class="sidebarOpen ? 'md:w-64 w-64' : 'w-16'"
     class="hidden md:flex top-0 left-0 bg-gray-800 shadow-lg z-50 transition-all duration-300 overflow-hidden h-auto">

    <!-- Botão sempre visível no topo -->
    <div class="p-2 w-20 absolute top-0 left-0 z-10 ">
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="bg-gray-800 rounded p-1 focus:outline-none"
            aria-label="Alternar menu"
        >
            <svg x-show="sidebarOpen" class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <svg x-show="!sidebarOpen" class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Conteúdo do menu lateral -->
    <div x-show="sidebarOpen" class="overflow-hidden pt-12 h-full">
        <div class="h-full flex flex-col justify-between">
            <div class="flex flex-col items-center mt-8">
                <div class="flex items-center space-x-4 p-2 mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center  text-white bg-gray-700">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-white">ID: {{ Auth::user()->id }}</div>
                    </div>
                </div>
                <nav class="w-full flex flex-col items-start space-y-2 px-4 text-white">
                    <x-dropdown-nav-link title="Dashboards">
                        <li>
                            <x-dropdown-nav-link-item href="{{ route('dashboard.calls') }}">
                                Chamadas
                            </x-dropdown-nav-link-item>
                        </li>
                    </x-dropdown-nav-link>
                    <x-nav-link href="{{ route('oportunidades.index') }}">Oportunidades</x-nav-link>
                    <x-nav-link href="{{ route('clientes.index') }}">Clientes</x-nav-link>
                    <x-nav-link href="{{ route('integracoes.index') }}">Integrações</x-nav-link>
                </nav>
            </div>
        </div>
    </div>
</aside>