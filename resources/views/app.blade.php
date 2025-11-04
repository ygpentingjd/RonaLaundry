<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Ganti title -->
        <title inertia>RonaLaundry</title>

        <!-- Tambahkan favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Meta tambahan (opsional) -->
        <meta name="description" content="RonaLaundry - Solusi laundry modern dan cepat.">
        <meta name="theme-color" content="#FF66B2">

        <!-- Inertia + Vite -->
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
