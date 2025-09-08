<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/charts.js'])
    @livewireStyles
    <script defer src="//unpkg.com/alpinejs"></script>
</head>
<body class="font-sans antialiased bg-gray-900 text-white">
    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen w-full">
        <!-- Menu lateral minimizável -->
       
        @include('lateral-menu.index')
       
        <!-- Conteúdo principal ocupa o restante -->
        <div class="flex-1 flex flex-col transition-all duration-300">
            @include('layouts.navigation')
            <main class="flex-1 flex flex-col items-center justify-start px-4 py-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
