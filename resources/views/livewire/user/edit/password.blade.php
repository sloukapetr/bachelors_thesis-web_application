<div class="mt-10 sm:mt-0">
    <x-form-section submit="setPassword">
        <x-slot name="title">
            {{ __('Nastavit nové heslo') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Nastavení nového hesla uživateli.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="password" value="{{ __('New Password') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
                <x-input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
                <x-input-error for="password_confirmation" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Heslo bylo nastaveno.') }}
            </x-action-message>

            <x-button>
                {{ __('Nastavit heslo') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
