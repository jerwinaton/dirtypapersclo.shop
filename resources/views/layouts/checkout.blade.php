<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} | Checkout</title>
    <meta name="description" content="Checkout.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <script src="https://js.stripe.com/v3/"></script>
    @livewireStyles
    <!-- @stripeScripts -->
</head>

<body class="antialiased text-gray-900">
    @livewire('components.navigation')


    <main>
        {{ $slot }}
    </main>

    <x-footer />

    @livewireScripts
</body>

</html>