<div class="space-y-5">
    <x-slot name="header">
        {{ __('Domů') }}
    </x-slot>

@if (!empty($weatherForecast))
    <div class="stats rounded-lg bg-base-200 border border-base-300 shadow-lg mb-6 w-full">

        <div class="stat place-items-center">
            <div class="stat-title">Aktuální počasí</div>
            <div class="stat-value">
                <img class="w-1/4 mx-auto py-2" src="/images/weather/{{ $weatherForecast['currentConditions']['icon'] }}.svg">
                <p class="text-lg text-center">{{ $weatherForecast['resolvedAddress'] }}</p>
            </div>
        </div>

        <div class="stat place-items-center">
            <div class="stat-title">Teplota</div>
            <div class="stat-value">{{ $weatherForecast['currentConditions']['temp'] }} °C</div>
            <div class="stat-desc">
                <div class="text-center">
                    @if (App\Models\SensorOutside::all()->count() > 0)
                        <div class="text-base text-base-content">Teplota ze senzoru {{ App\Models\SensorOutside::latest()->first()->temp }} °C</div>
                        <div class="@if(Carbon\Carbon::now()->diffInSeconds(App\Models\SensorOutside::latest()->first()->created_at) > config('custom.sensor_last_update')) badge badge-error gap-2 @else text-base-content/70 @endif">{{ __('Naposledy měřeno') }} {{ App\Models\SensorOutside::latest()->first()->created_at->diffForHumans() }}</div>
                    @else
                    <div class="badge badge-error gap-2">{{ _('Venkovní teplota neměřena.') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="stat place-items-center">
        <div class="stat-title">Vlhkost vzduchu</div>
        <div class="stat-value text-secondary">{{ $weatherForecast['currentConditions']['humidity'] }} %</div>
        <div class="stat-desc">Tlak: {{ $weatherForecast['currentConditions']['pressure'] }} hPa</div>
        </div>

        <div class="stat place-items-center">
        <div class="stat-title">Oblačnost</div>
        <div class="stat-value text-secondary">{{ $weatherForecast['currentConditions']['cloudcover'] }} %</div>
        <div class="stat-desc">Děšť {{ $weatherForecast['currentConditions']['precip'] }} %</div>
        </div>

    </div>

@else

    <div>
        <x-messages.warning>
            <x-slot name="header">
                {{ __('Chyba při načítání předpovědi počasí!') }}
            </x-slot>
            <x-slot name="message">
                <ul class="list-inside list-disc text-sm">
                    <li>{{ __('Chyba při načítání předpovědi počasí. Může být špatně nastaven klíč nebo cílová lokace.') }}</li>
                    <li>{{ __('Pokud chyba přetrvává, kontaktuj vývojáře.') }}</li>
                </ul>
            </x-slot>
        </x-messages.warning>
    </div>

@endif

<div class="content-window">
    <div class="content-window-header">{{ __('Jak používat tuto aplikaci') }}</div>
    <div class="text-justify">
        {{ __('Prvnotní spuštění aplikace a nastavení automatizovaného vytápění je kompletně popsáno v textové čísti, která se zabývá touto prací.') }}
        {{ __('Celá práce je dostupná na webové stránce') }} <a class="link" href="https://www.vut.cz/studenti/zav-prace/detail/151076" target="_blank">{{ __('závěrečných prací') }}</a> {{ __('VUT v Brně') }}.
    </div>
    <h2 class="mt-2">{{ __('Místnosti') }}</h2>
    <div class="text-justify">
        <ul class="list-disc list-inside">
            <li>{{ __('Pro každou místnost je potřeba, aby jednotlivým uživatelům přiřadil správce aplikace oprávnění, poté je možné nastavit požadovanou teplotu.') }}</li>
            <li>{{ __('Všichni uživatelé mohou nahlížet do jednotlivých místností.') }}</li>
            <li>{{ __('Správci jsou rovněž zobrazovány další hodnoty, pokud je toto nastavení zapnuto.') }}</li>
        </ul>
    </div>
    <h2 class="mt-2">{{ __('Uživatelé') }}</h2>
    <div class="text-justify">
        <ul class="list-disc list-inside">
            <li>{{ __('První vytvořený (zaregistrovaný) uživatel je správce.') }}</li>
            <li>{{ __('Je doporučeno zapnout dvoufaktorové ověření a vypnout registrace do systému po prvotní registraci. Poté může další uživatele přidávat správce.') }}</li>
            <li>{{ __('Dále je doporučeno nastavit emailový server pro obnovu hesla.') }}</li>
        </ul>
    </div>
</div>

</div>
