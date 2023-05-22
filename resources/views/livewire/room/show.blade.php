<div>

    <x-slot name="title">
        {{ $room->getName() }}
    </x-slot>

    <x-slot name="header">
        {{ $room->getName() }}
    </x-slot>


    <div class="content-window">
        <div class="content-window-header">
            {{ $room->getName() }}
            @if (config('custom.show_floors'))
                <div class="text-sm opacity-50">{{ $room->getFloor() }}</div>
            @endif
        </div>
        <div>
            {{ __('Teplota') }}: {{ $room->getRoomTemp() }}
            <span class="text-sm text-base-content/80">{{ __('Cílová teplota') }}: {{ $room->getGoalTemp() }}</span>
        </div>
        <div>
            {{ __('Vlhkost') }}: {{ $room->getHumidity() }}
        </div>
    </div>

    @if ($room->sensors()->count() > 10)
        <div class="content-window">
            <div class="content-window-header">{{ __('Teplota a vlhkost za poslední den') }}</div>
            <livewire:livewire-line-chart
                key="{{ $graph_dayBefore->reactiveKey() }}"
                :line-chart-model="$graph_dayBefore"
            />
        </div>

        <div class="content-window">
            <div class="content-window-header">{{ __('Teplota a vlhkost za poslední týden') }}</div>
            <livewire:livewire-line-chart
                key="{{ $graph_weekBefore->reactiveKey() }}"
                :line-chart-model="$graph_weekBefore"
            />
        </div>

        <div class="content-window">
            <div class="content-window-header">{{ __('Teplota a vlhkost za poslední měsíc') }}</div>
            <livewire:livewire-line-chart
                key="{{ $graph_monthBefore->reactiveKey() }}"
                :line-chart-model="$graph_monthBefore"
            />
        </div>

        <div class="content-window">
            <div class="content-window-header">{{ __('Všechna data') }}</div>
            <livewire:livewire-line-chart
                key="{{ $graph_all->reactiveKey() }}"
                :line-chart-model="$graph_all"
            />
        </div>
    @else
        <x-messages.info>
            <x-slot name="header">
                {{ __('V databázi je velmi málo hodnot!') }}
            </x-slot>
            <x-slot name="message">
                <ul class="list-inside list-disc text-sm">
                    <li>{{ __('V databázi je velmi málo hodnot pro zobrazení grafů.') }}</li>
                    <li>{{ __('Přiřezených hodnot k této místnosti je') }}: {{ $room->sensors()->count() }}. {{ __('Pro zobrazení grafů je potřeba alespoň 10 záznamů.') }}</li>
                </ul>
            </x-slot>
        </x-messages.info>
    @endif

</div>
