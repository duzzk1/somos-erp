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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-image: url('{{ asset('images/banner-adapta-erp.jpg') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        <div class="min-h-screen flex sm:justify-center items-center pt-6 sm:pt-0 bg-black dark:bg-gray-900 bg-opacity-60">
            <div class="fixed right-10 w-full sm:max-w-md mt-6 px-6 py-4 items-center bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <x-application-logo class="fill-current text-gray-500 w-42" />
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
