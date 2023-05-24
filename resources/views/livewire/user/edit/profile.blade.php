<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            {{ __('Profile Information') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Aktualizuj informace o uživateli.') }}
        </x-slot>

        <x-slot name="form">
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Username') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" autocomplete="name" />
                <x-input-error for="name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                <x-input-error for="email" class="mt-2" />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2">
                        {{ __('Email uživatele nebyl ověřen.') }}
                    </p>
                @endif
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Uloženo.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="submit">
                {{ __('Aktualizovat profil') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
