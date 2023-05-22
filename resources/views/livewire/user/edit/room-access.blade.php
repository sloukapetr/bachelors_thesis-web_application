<div class="mt-10 sm:mt-0">
    <x-form-section submit="submit">
        <x-slot name="title">
            {{ __('Oprávnění k místnostem') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Nastav uživateli oprávnění k určitým místnostem, aby mohl nastavovat požadovanou teplotu vytápění.') }}
        </x-slot>

        <x-slot name="form">
            @if ($rooms->count() > 0)
                <div class="col-span-6 space-y-2">
                    @foreach ($rooms as $room)
                        <label class="flex items-center gap-2 cursor-pointer input-same-sm">
                            <input type="checkbox" class="toggle toggle-primary toggle-sm" wire:model="room_permission.{{$room->id}}">
                            <span class="label-text">{{ $room->name }} <span class="text-xs text-base-content/60">{{ $room->floor }}. {{ __('floor') }}</span></span>
                        </label>
                        <x-input-error for="room_permission.{{$room->id}}" />
                    @endforeach
                </div>
            @else
                <div class="col-span-6">
                    <x-messages.info>
                        <x-slot name="message">
                            {{ __('There are no rooms where can be assigned and access.') }}
                        </x-slot>
                    </x-messages.info>
                </div>
            @endif
        </x-slot>

        @if ($rooms->count() > 0)
            <x-slot name="actions">
                <x-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-action-message>

                <x-button>
                    {{ __('Save') }}
                </x-button>
            </x-slot>
        @endif
    </x-form-section>
</div>
