<div>

    <x-slot name="title">
        {{ __('O aplikaci') }}
    </x-slot>

    <x-slot name="header">
        {{ __('O aplikaci') }}
    </x-slot>

    @if (!empty(Auth::user()) AND Auth::user()->is_admin)
    <div class="content-window">
        <div class="content-window-header">{{ __('Nastavení aplikace') }}</div>
        <div class="overflow-x-auto w-full">
            <table class="table w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th>{{ __('Název možnosti') }}</th>
                    <th>{{ __('Hodnota') }}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <!-- row 1 -->
                    <tr class="hover">
                        <th>
                            <div class="font-bold">{{ $setting->getName() }}</div>
                            <div class="text-xs text-base-content/80">{!! $setting->getDescription() !!}</div>
                        </th>
                        <td>
                            <div>{{ $setting->getValue() }}</div>
                        </td>
                        <th>
                            @if (Auth::user()->is_admin)
                                @if ($setting->getName() != "water_temp")
                                    <button class="btn btn-ghost btn-xs" wire:click.prevent="updateShowModal({{ $setting->id }})">{{ __('Upravit') }}</button>
                                @endif
                            @endif
                        </th>
                    </tr>
                </tbody>
                @endforeach
            <!-- foot -->
            <tfoot>
                <tr>
                    <th>{{ __('Název možnosti') }}</th>
                    <th>{{ __('Hodnota') }}</th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>

            </table>
        </div>
    </div>

    <div class="content-window">
        <div class="content-window-header">{{ __('Údaje ze souboru config/custom.php') }}</div>
        <div class="overflow-x-auto w-full">
            <table class="table w-full">
            <!-- head -->
            <thead>
                <tr>
                    <th>{{ __('Název možnosti') }}</th>
                    <th>{{ __('Hodnota') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach (config('custom') as $key => $value)
                    <!-- row 1 -->
                    <tr class="hover">
                        <th>
                            <div class="font-bold">{{ $key }}</div>
                        </th>
                        <td>
                            <div>
                                @if ($value == 1 || $value == "true")
                                    {{ __('true') }}
                                @elseif ($value == 0 || $value == "false" || empty($value))
                                    {{ __('false') }}
                                @else
                                    {{ $value }}
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforeach
            <!-- foot -->
            <tfoot>
                <tr>
                    <th>{{ __('Název možnosti') }}</th>
                    <th>{{ __('Hodnota') }}</th>
                </tr>
            </tfoot>

            </table>
        </div>
    </div>
    @endif

    <div class="content-window">
        <div class="content-window-header">{{ __('O bakalářské práci') }}</div>
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Consequuntur sint doloribus corrupti totam itaque quo obcaecati, rerum nesciunt maxime blanditiis molestias reprehenderit aperiam alias? Commodi tempore quisquam optio molestias accusantium.
    </div>

    <div class="content-window">
        <div class="content-window-header">{{ __('O aplikaci') }}: {{ __('Automatizované řízení vytápění rodinného domu') }}</div>
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Consequuntur sint doloribus corrupti totam itaque quo obcaecati, rerum nesciunt maxime blanditiis molestias reprehenderit aperiam alias? Commodi tempore quisquam optio molestias accusantium.
    </div>

    @if ($this->modalItem)
        {{-- The Create Modal --}}
        <x-dialog-modal wire:model="modalUpdateVisible">
            <x-slot name="title">
                <h1>
                    {{ __('Upravit nastavení') }}:  {{ $this->modalItem->getName() }}
                </h1>
            </x-slot>

            <x-slot name="content">
                <x-validation-errors class="mb-4" />

                <div class="text-lg text-base-content/80">{{ __('Popis') }}</div>

                <div>{!! $this->modalItem->getDescription() !!}</div>

                <form wire:submit.prevent="update">

                    <div class="grid grid-cols-1 gap-2">
                        <div class="form-control">
                            <x-label for="value" value="{{ __('Hodnota') }}" required />
                            <x-input id="value" class="w-full {{ $errors->has('value') ? 'input-error' : '' }} input-sm" type="text" :value="old('value')" wire:model="value" placeholder="" required autofocus />
                            <x-input-error for="value" />
                        </div>
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <div class="flex gap-2">
                    <button class="btn btn-ghost btn-sm" wire:click="$toggle('modalUpdateVisible')" wire:loading.attr="disabled">
                        {{ __('Zavřít') }}
                    </button>
                    <x-button class="btn-sm ml-2" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Uložit') }}
                    </x-button>
                </div>
            </x-slot>

        </x-dialog-modal>
    @endif

</div>
