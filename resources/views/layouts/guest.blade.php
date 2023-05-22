<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ !empty($title) ? "$title |" : "" }} {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-base-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-base-200 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl leading-tight text-base-content">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="max-w-7xl mx-auto py-6 md:px-4 sm:px-6 lg:px-8">
                    @include('layouts.includes.messages')
                    {{ $slot }}
                </div>
                <div class="flex justify-center my-2">
                    <div class="form-control w-full max-w-xs">
                    <label class="label">
                        <span class="label-text">{{ __('Vybrat vzhled stránky') }}</span>
                    </label>
                    <select data-choose-theme class="select select-bordered select-sm">
                        <option value="">{{ __('Výchozí podle systému') }}</option>
                        <option value="light">{{ __('Světlý') }}</option>
                        <option value="dark">{{ __('Tmavý') }}</option>
                    </select>
                    </div>
                </div>
                <div class="w-full p-2 text-xs text-base-content/60 text-center">
                    <div>{{ __('Stránka byla vygenerována za') }} {{ round(((microtime(true) - LARAVEL_START)), 2) }} {{ __('sekund') }}. {{ __('PHP verze') }}: {{ phpversion() }}. {{ __('Laravel verze') }}: {{ app()->version() }}.</div>
                    <div>{{ __('Serverové datum') }}: {{ now()->format('d. m. Y H:i') }}</div>
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
