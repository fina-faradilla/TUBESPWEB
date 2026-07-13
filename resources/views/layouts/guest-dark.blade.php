<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RoadFix') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0b0e14]">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

            <a href="{{ route('landing') }}" class="flex items-center gap-2 mb-8">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-orange-500 text-[#0b0e14] font-extrabold text-sm">RF</span>
                <span class="text-white font-extrabold tracking-wide">RoadFix</span>
            </a>

            <div class="w-full max-w-md">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>