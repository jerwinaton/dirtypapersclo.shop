<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="E-commerce Dirty Papers Clo.">

    <!-- Styles -->
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    @livewireStyles
    <!-- Scripts -->
</head>

<body class="antialiased text-gray-900">
    @livewire('components.navigation')

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    @livewireScripts
</body>

</html>