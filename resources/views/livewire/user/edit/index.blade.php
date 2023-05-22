<div>
    <x-slot name="title">
        {{ __('Upravit uživatele') }}: {{ $user->getName() }}
    </x-slot>
    <x-slot name="header">
        {{ __('Upravit uživatele') }}: <strong>{{ $user->getName() }}</strong>
    </x-slot>

    @livewire('user.edit.profile', [$user])

    <x-section-border />

    @livewire('user.edit.password', [$user])

    @can('update', $model = $user)
        <x-section-border />
        @livewire('user.edit.room-access', [$user])
    @endcan

</div>
