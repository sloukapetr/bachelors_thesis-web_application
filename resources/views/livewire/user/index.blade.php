<div>
    <x-slot name="title">
        {{ __('Uživatelé') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Uživatelé') }}
    </x-slot>
    <div class="text-center md:text-right mb-2">
        @can('create', App\Models\User::class)
            <button class="btn btn-primary btn-sm gap-2" wire:click.prevent="createShowModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('Přidat uživatele') }}
            </button>
        @endcan
    </div>
    <div class="content-window">
        <div class="content-window-header">{{ __('Seznam uživatelů') }}</div>
        <div class="overflow-x-auto w-full">
            <table class="table w-full">
              <!-- head -->
              <thead>
                <tr>
                  <th>{{ __('Jméno') }}</th>
                  <th>{{ __('E-mail') }}</th>
                  <th>{{ __('Přístup do místností') }}</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users->whereNull('deleted_at') as $user)
                    <!-- row 1 -->
                    <tr class="hover">
                        <th>
                            <div>
                                <div class="font-bold">{{ $user->getName() }}</div>
                                @if ($user->is_admin)
                                    <div class="text-xs text-success">{{ __('Správce') }}</div>
                                @endif
                            </div>
                        </th>
                        <td>
                            {{ $user->getEmail() }}
                        </td>
                        <td>
                            @if ($user->is_admin)
                                <span class="text-sm text-info">{{ __('Uživatel s oprávněním správce může upravovat všechny místnosti.') }}</span>
                            @elseif ($user->rooms()->count() > 0)
                                    <div>
                                        @foreach ($user->rooms()->get() as $room)
                                            <span class="text-sm">{{ $room->name }}</span>
                                            @if (!$loop->last)
                                            &middot;
                                            @endif
                                        @endforeach
                                    </div>
                            @else
                                <span class="text-sm text-base-content/80">{{ __('Uživatel nemá práva pro žádnou místnost.') }}</span>
                            @endif
                        </td>
                        <th>
                            @can('view', $model = $user)
                                <button class="btn btn-ghost btn-xs" wire:click.prevent="showShowModal({{ $user->id }})">{{ __('Show') }}</button>
                            @endcan
                            @if ($user->is_admin == 0)
                                @can('update', $model = $user)
                                    <a class="btn btn-ghost btn-xs" href="{{ route('users.edit', compact('user')) }}">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete', $model = $user)
                                    <button class="btn btn-ghost btn-xs" wire:click.prevent="deleteShowModal({{ $user->id }})">{{ __('Remove') }}</button>
                                @endcan
                            @endif
                        </th>
                    </tr>
                </tbody>
                @endforeach
              <!-- foot -->
              <tfoot>
                <tr>
                    <th>{{ __('Jméno') }}</th>
                    <th>{{ __('E-mail') }}</th>
                    <th>{{ __('Přístup do místností') }}</th>
                    <th>&nbsp;</th>
                </tr>
              </tfoot>

            </table>
        </div>
    </div>

    @if ($users->whereNotNull('deleted_at')->count() > 0)
        <div class="content-window">
            <div class="content-window-header">{{ __('Koš uživatelů') }}</div>
            @if ($users->whereNotNull('deleted_at')->count() > 0)
                <div class="overflow-x-auto w-full">
                    <table class="table w-full">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>{{ __('Jméno') }}</th>
                            <th>{{ __('E-mail') }}</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users->whereNotNull('deleted_at') as $user)
                            <!-- row 1 -->
                            <tr class="hover">
                                <th>
                                    <div class="font-bold">{{ $user->getName() }}</div>
                                </th>
                                <td>
                                    <div>{{ $user->getEmail() }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-base-content/60">{{ __('Uživatel je v koši, není možné se s těmito údaji přihlásit do systému.') }}</span>
                                </td>
                                <th>
                                    @can('view', $user)
                                        <button class="btn btn-ghost btn-xs" wire:click.prevent="showShowModal({{ $user->id }})">{{ __('Show') }}</button>
                                    @endcan
                                    @can('update', $model = $user)
                                        <a class="btn btn-ghost btn-xs" href="{{ route('users.edit', compact('user')) }}">{{ __('Edit') }}</a>
                                    @endcan
                                    @can('restore', $model = $user)
                                        <button class="btn btn-ghost btn-xs" wire:click.prevent="restoreShowModal({{ $user->id }})">{{ __('Restore') }}</button>
                                    @endcan
                                    @can('forceDelete', $model = $user)
                                        <button class="btn btn-ghost btn-xs" wire:click.prevent="forceDeleteShowModal({{ $user->id }})">{{ __('Force delete') }}</button>
                                    @endcan
                                </th>
                            </tr>
                        </tbody>
                        @endforeach
                    <!-- foot -->
                    <tfoot>
                        <tr>
                            <th>{{ __('Jméno') }}</th>
                            <th>{{ __('E-mail') }}</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>

                    </table>
                </div>
            @else
                <x-messages.info>
                    <x-slot name="message">
                        {{ __('Žádný uživatel v koši.') }}
                    </x-slot>
                </x-messages.info>
            @endif
        </div>
    @endif

    @if ($this->modalItem)
        {{-- The Delete Modal --}}
        <x-confirmation-modal wire:model="modalConfirmDeleteVisible">
            <x-slot name="title">
                {{ __('Odstranit uživatele') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('Jsi si jistý? Opravdu si přeješ odstranit uživatele') }} <strong>{{ $this->modalItem->getName() }}</strong>?</p>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button class="btn-sm" wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                    {{ __('Zavřít') }}
                </x-secondary-button>

                <x-danger-button class="btn-sm ml-2" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Remove') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>

        {{-- The Restore Modal --}}
        <x-confirmation-modal wire:model="modalConfirmRestoreVisible">
            <x-slot name="title">
                {{ __('Obnovit člena') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('Opravdu chceš obnovit člena') }} <strong>{{ $this->modalItem->getName() }}</strong>?</p>

            </x-slot>

            <x-slot name="footer">
                <x-secondary-button class="btn-sm" wire:click="$toggle('modalConfirmRestoreVisible')" wire:loading.attr="disabled">
                    {{ __('Zavřít') }}
                </x-secondary-button>

                <x-button class="ml-2 btn-sm" wire:click="restore" wire:loading.attr="disabled">
                    {{ __('Obnovit') }}
                </x-button>
            </x-slot>
        </x-confirmation-modal>

        {{-- The Force Delete Modal --}}
        <x-confirmation-modal wire:model="modalConfirmForceDeleteVisible">
            <x-slot name="title">
                {{ __('Trvale odstranit uživatele?') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('Jsi si jistý? Opravdu si přeješ trvale odstranit uživatele') }} <strong>{{ $this->modalItem->getName() }}</strong>?</p>

            </x-slot>

            <x-slot name="footer">
                <x-secondary-button class="btn-sm" wire:click="$toggle('modalConfirmForceDeleteVisible')" wire:loading.attr="disabled">
                    {{ __('Zavřít') }}
                </x-secondary-button>

                <x-danger-button class="btn-sm ml-2" wire:click="forceDelete" wire:loading.attr="disabled">
                    {{ __('Force delete') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>

        {{-- The Show Modal --}}
        <x-dialog-modal wire:model="modalShowVisible">
            <x-slot name="title">
                <h1>
                    {{ __('Náhled uživatele') }}
                </h1>
            </x-slot>

            <x-slot name="content">
                <table class="table table-sm w-full">
                    <tbody>
                        <tr>
                            <th>{{ __('Jméno') }}</th>
                            <td>
                                {{ $this->modalItem->getName() }}
                                @if ($this->modalItem->deleted_at != null)
                                    <span class="text-sm text-error">{{ __('Uživatelský účet je v koši.') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('E-mail') }}</th>
                            <td>
                                {{ $this->modalItem->getEmail() }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Dvoufázové ověřování') }}</th>
                            <td>
                                @if (!empty($this->modalItem->two_factor_secret))
                                    {{ __('Aktivní') }}
                                @else
                                    {{ __('Neaktivní') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Typ účtu') }}</th>
                            <td class="text-sm text-base-content/80">
                                @switch($this->modalItem->is_admin)
                                    @case(1)
                                        <div class="text-success">{{ __('Účet správce') }}</div>
                                        @break
                                    @default
                                        <div>{{ __('Standardní účet') }}</div>
                                @endswitch
                            </td>
                        </tr>
                        @if (empty($this->modalItem->deleted_at))
                            <tr>
                                <th>{{ __('Přístup do místností') }}</th>
                                <td>
                                    @if ($this->modalItem->is_admin)
                                        <span class="text-info">{{ __('Uživatel s oprávněním správce může upravovat všechny místnosti.') }}</span>
                                    @elseif ($this->modalItem->rooms()->count() > 0)
                                            <ul class="list-disc list-inside text-sm">
                                                @foreach ($this->modalItem->rooms()->get() as $room)
                                                    <li>{{ $room->name }}</li>
                                                @endforeach
                                            </ul>
                                    @else
                                        <x-messages.info>
                                            <x-slot name="message">
                                                {{ __('Uživatel nemá práva pro žádnou místnost.') }}
                                            </x-slot>
                                        </x-messages.info>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </x-slot>

            <x-slot name="footer">
                <div class="flex gap-2">
                    <button class="btn btn-ghost btn-sm" wire:click="$toggle('modalShowVisible')" wire:loading.attr="disabled">
                        {{ __('Zavřít') }}
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>

    @endif

    {{-- The Create Modal --}}
    <x-dialog-modal wire:model="modalCreateVisible">
        <x-slot name="title">
            <h1>
                {{ __('Přidat uživatele') }}
            </h1>
        </x-slot>

        <x-slot name="content">
            <x-validation-errors class="mb-4" />

            <form wire:submit.prevent="create">

                <div class="grid grid-cols-1 gap-2">
                    <div class="form-control">
                        <x-label for="name" value="{{ __('Jméno') }}" required />
                        <x-input id="name" class="w-full {{ $errors->has('name') ? 'input-error' : '' }} input-sm" type="text" :value="old('name')" wire:model="name" placeholder="" required autofocus />
                        <x-input-error for="name" />
                    </div>
                    <div class="form-control">
                        <x-label for="email" value="{{ __('E-mail') }}" required />
                        <x-input id="email" class="w-full {{ $errors->has('email') ? 'input-error' : '' }} input-sm" type="text" :value="old('email')" wire:model="email" placeholder="" required autocomplete="email" />
                        <x-input-error for="email" />
                    </div>
                    <div class="form-control">
                        <x-label for="password" value="{{ __('Heslo') }}" />
                        <x-input id="password" class="w-full {{ $errors->has('password') ? 'input-error' : '' }} input-sm" type="password" :value="old('password')" wire:model="password" placeholder="" required autocomplete="new-password" />
                        <x-input-error for="password" />
                    </div>
                    <div class="form-control">
                        <x-label for="password_confirmation" value="{{ __('Kontrola hesla') }}" />
                        <x-input id="password_confirmation" class="w-full {{ $errors->has('password_confirmation') ? 'input-error' : '' }} input-sm" type="password" :value="old('password_confirmation')" wire:model="password_confirmation" placeholder="" required autocomplete="new-password" />
                        <x-input-error for="password_confirmation" />
                    </div>
                    <div class="form-control">
                        <x-label value="{{ __('Oprávnění pro místnosti') }}" />
                        @if ($rooms->count() > 0)
                            <div class="space-y-2">
                                @foreach ($rooms as $room)
                                    <label class="flex items-center gap-2 cursor-pointer input-same-sm">
                                        <input type="checkbox" class="toggle toggle-primary toggle-sm" wire:model="room_permission.{{$room->id}}">
                                        <span class="label-text">{{ $room->name }} <span class="text-xs text-base-content/60">{{ $room->getFloor() }}</span></span>
                                    </label>
                                    <x-input-error for="room_permission.{{$room->id}}" />
                                @endforeach
                            </div>
                        @else
                            <x-messages.info>
                                <x-slot name="message">
                                    {{ __('Žádná místnost neexistuje.') }}
                                </x-slot>
                            </x-messages.info>
                        @endif
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <div class="flex gap-2">
                <button class="btn btn-ghost btn-sm" wire:click="$toggle('modalCreateVisible')" wire:loading.attr="disabled">
                    {{ __('Zavřít') }}
                </button>
                <x-button class="btn-sm ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Přidat uživatele') }}
                </x-button>
            </div>
        </x-slot>

    </x-dialog-modal>

</div>
