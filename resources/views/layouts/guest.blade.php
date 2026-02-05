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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            {{ $slot }}
        </div>
        <footer class="m-12 flex flex-col gap-4 border-t border-slate-200 pt-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 text-slate-600">
                <div class="h-8 w-8 rounded-lg bg-slate-900"></div>
                <a href="/" class="font-semibold text-slate-900">EduCore</a>
            </div>
            <div class="flex flex-wrap gap-4">
                <span>Privacy</span>
                <span>Terms</span>
                <span>Support</span>
            </div>
            <span>&copy; {{ date('Y') }} EduCore. All rights reserved.</span>
        </footer>
    </body>
</html>
