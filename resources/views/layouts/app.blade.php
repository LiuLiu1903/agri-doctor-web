<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Agri Doctor') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-100 to-gray-200">

<div class="min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">

        <div class="h-16 flex items-center justify-center border-b">
            <h1 class="text-xl font-bold text-green-600">🌿 Agri AI</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-600 transition">
                🏠 Dashboard
            </a>

            <a href="#"
               class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-600 transition">
                🤖 Chẩn đoán AI
            </a>

        </nav>

        <div class="p-4 border-t text-sm text-gray-500">
            {{ auth()->user()->email ?? '' }}
        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="h-16 bg-white shadow flex items-center justify-between px-6">

            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $header ?? '' }}
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->name ?? '' }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-600">
                        Logout
                    </button>
                </form>
            </div>

        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-6 overflow-y-auto">
            {{ $slot }}
        </main>

    </div>

</div>

</body>
</html>