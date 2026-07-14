<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen relative overflow-hidden bg-gray-200 flex items-center justify-center">

        {{-- Pink wave background --}}
        <svg class="absolute bottom-0 right-0 w-full h-auto pointer-events-none" viewBox="0 0 1041 300" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M1041 60C850 20 700 180 500 220C300 260 150 200 0 240V300H1041V60Z" fill="url(#pinkGradient)"/>
            <defs>
                <linearGradient id="pinkGradient" x1="1041" y1="60" x2="200" y2="300" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#DB2777"/>
                    <stop offset="1" stop-color="#F472B6"/>
                </linearGradient>
            </defs>
        </svg>

        <div class="relative z-10 w-full sm:max-w-md px-6 py-10 mx-4 bg-pink-50 rounded-2xl shadow-xl">
            {{ $slot }}
        </div>

    </div>
</body>
</html>