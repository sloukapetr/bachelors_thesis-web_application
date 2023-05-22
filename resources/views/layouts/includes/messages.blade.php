<div class="space-y-5 mb-5">

    {{-- Profil a nastavení --}}
    @if (Auth::user())
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication() AND empty(Auth::user()->two_factor_confirmed_at))
            <div class="max-w-7xl mx-auto pt-4">
                <div class="alert alert-warning shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <h3 class="font-bold">{{ __('Upozornění! Chybí dvoufázové ověření!') }}</h3>
                            <div class="text-sm">{{ __('Nemáš nastavené dvoufázové ověření, nebudeš mít možnost kamkoli přistupovat a budeš vždy přesměrován na tuto stránku.') }}</div>
                            <div class="text-sm">{{ __('Pokud jsi právě nastavil dvoufázové ověření, načti stránku znovu např. stisknutím tlačítka F5.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- zpravy pro spravce --}}
        {{--  @if (config('custom.show_admin_messages'))  --}}
            @if (Auth::user()->is_admin AND Laravel\Fortify\Features::canManageTwoFactorAuthentication() == false)
                <x-messages.warning>
                    <x-slot name="header">
                        {{ __('Dvoufázové ověřování je vypnuto!') }}
                    </x-slot>
                    <x-slot name="message">
                        <ul class="list-inside list-disc text-sm">
                            <li>{{ __('V rámci zabezpečení přístupu do aplikace je doporučeno aktivovat dvoufázové ověření.') }}</li>
                            <li>{{ __('Aktivovat dvoufázové ověřování je možné pouze zásahem do konfigurace webové aplikace v jejich souborech, a to: /config/fortify.php.') }}</li>
                            <li>{{ __('Tuto informaci vidí pouze správce.') }}</li>
                        </ul>
                    </x-slot>
                </x-messages.warning>
            @endif

            @if (Auth::user()->is_admin AND Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
                <x-messages.warning>
                    <x-slot name="header">
                        {{ __('Stále je možné se registrovat do systému!') }}
                    </x-slot>
                    <x-slot name="message">
                        <ul class="list-inside list-disc text-sm">
                            <li>{{ __('Vypni tuto možnost pro zabránění registrací nežádoucím uživatelům v nastavení webové stránky.') }}</li>
                            <li>{{ __('Aktivovat dvoufázové ověřování je možné pouze zásahem do konfigurace webové aplikace v jejich souborech, a to: /config/fortify.php.') }}</li>
                            <li>{{ __('Tuto informaci vidí pouze správce.') }}</li>
                        </ul>
                    </x-slot>
                </x-messages.warning>
            @endif
        {{-- @endif --}}
    @endif

    {{-- Úkoly --}}
    @if (session()->has('task-added'))
        <x-messages.success>
            <x-slot name="header">
                {{ __('Nový úkol byl úspěšně přidán.') }}
            </x-slot>
            <x-slot name="message">
                <ul class="list-inside list-disc text-sm">
                    <li>{{ __('Název úkolu') }}: {{ session('task')->title }}</li>
                    <li>{{ __('Byl jsi automaticky přiřazen k úkolu.') }}</li>
                    <li>{{ __('Pokud chceš přiřadit k úkolu další uživatele nebo odebrat sebe, můžeš tak učinit v úpravách turnusu.') }}</li>
                    <li>{{ __('Poznámky lze přidávat v náhledu úkolu.') }}</li>
                </ul>
            </x-slot>
        </x-messages.success>
    @endif

    {{-- Špatná adresa počasí --}}
    @if (session('badWeatherAddress'))
        <x-messages.error>
            <x-slot name="header">
                {{ __('Neplatná adresa pro předpověď počasí!') }}
            </x-slot>
            <x-slot name="message">
                <ul class="list-inside list-disc text-sm">
                    <li>{{ __('Nastav platnou adresu v části address níže.') }}</li>
                    <li>{{ __('Ověřit si správně zadanou lokaci můžeš na webu') }} <a href="https://www.visualcrossing.com/weather-data" target="_blank">{{ __('visualcrossing.com') }}</a>.</li>
                    @if (Auth::user()->is_admin == 0)
                        <li>{{ __('Informuj o této události správce!') }}</li>
                    @endif
                </ul>
            </x-slot>
        </x-messages.error>
    @endif

</div>
