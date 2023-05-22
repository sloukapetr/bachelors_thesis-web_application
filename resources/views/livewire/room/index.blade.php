<div>

    <x-slot name="title">
        {{ __('Místnosti') }}
    </x-slot>

    <x-slot name="header">
        {{ __('Místnosti') }}
    </x-slot>

    @can('create', App\Models\Room::class)
        <div class="text-center md:text-right mb-2">
            <button class="btn btn-primary btn-sm gap-2" wire:click.prevent="createShowModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('Přidat místnosti') }}
            </button>
        </div>
    @endcan

    <div class="content-window">
        <div class="content-window-header">{{ __('Seznam místností') }}</div>
        <div class="overflow-x-auto w-full">
            <table class="table w-full">
              <!-- head -->
              <thead>
                <tr>
                  <th>{{ __('Název místnosti') }}</th>
                  <th>{{ __('Teplota') }}</th>
                  <th>{{ __('Vlhkost') }}</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rooms->whereNull('deleted_at') as $room)
                    <!-- row 1 -->
                    <tr class="hover">
                        <th>
                            <div>
                                <div class="font-bold">{{ $room->getName() }}</div>
                                @if (config('custom.show_floors'))
                                    <div class="text-sm opacity-50">{{ $room->getFloor() }}</div>
                                @endif
                                @if (Auth::user()->is_admin AND config('custom.show_admin_messages'))
                                <div class="text-info/80 text-sm">
                                    <strong>{{ __('[SPRÁVCE]') }}</strong> {{ __('ID') }}: {{ $room->id }}
                                </div>
                                <div class="text-info/80 text-xs">
                                    <strong>{{ __('[SPRÁVCE]') }}</strong> {{ __('Zaznamenaných hodnot') }}: {{ $room->sensors()->count() }}
                                </div>
                                @endif
                            </div>
                        </th>
                        <td>
                            <div>
                                {{ __('Teplota v místnosti') }}:
                                @if ($room->checkRoomTemp() )
                                    <span>{{ $room->getRoomTemp() }}</span>
                                    @if (Carbon\Carbon::now()->diffInSeconds($room->sensors()->latest()->first()->created_at) > config('custom.sensor_last_update'))
                                        <div class="badge badge-error gap-2">
                                            {{ __('Naposledy měřeno') }} {{ $room->sensors()->latest()->first()->created_at->diffForHumans() }}
                                        </div>
                                    @endif
                                @else
                                    <span class="badge badge-error gap-2">{{ $room->getRoomTemp() }}</span>
                                @endif
                            </div>
                            @if ($room->checkRoomTemp() > 0)
                                @if ($room->goal_temp > 0)
                                    <div class="text-base-content/80 text-sm">{{ __('Cílová teplota') }}: <strong>{{ $room->getGoalTemp() }}</strong></div>
                                @else
                                    <div class="text-info/80 text-xs">{{ __('Vytápění vypnuto!') }}</div>
                                @endif
                            @endif
                            @if (Auth::user()->is_admin AND config('custom.show_admin_messages'))
                                <div class="text-info/80 text-sm">
                                    <strong>{{ __('[SPRÁVCE]') }}</strong> {{ __('Hodnota otevření ventilu je:') }} <strong>{{ $room->valve_value }}</strong> <span class="text-xs">{{ __('(0 - zavřeno, 1 - otevřeno)') }}</span>
                                </div>
                            @endif
                            @can('setTemp', $room)
                                @if ($room->checkRoomTemp() > 0)
                                    <div class="mt-2 justify-center flex gap-2">
                                        <button class="btn btn-ghost btn-xs btn-outline" wire:click.prevent="setTempShowModal({{ $room->id }})">
                                            {{ __('Nastavit teplotu') }}
                                        </button>
                                        @if (!empty($room->goal_temp))
                                            <button class="btn btn-error btn-xs btn-outline" wire:click.prevent="setHeatingOff({{ $room->id }})">
                                                {{ __('Vypnout topení') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @endcan
                        </td>
                        <td>
                            @if (!empty($room->humidity))
                                <span>{{ $room->getHumidity() }}</span>
                            @else
                                <span class="text-base-content/60 text-xs">{{ $room->getHumidity() }}</span>
                            @endif
                        </td>
                        <th>
                            @can('view', $room)
                                <a class="btn btn-ghost btn-xs" href="{{ route('rooms.show', compact('room')) }}">{{ __('Show') }}</a>
                            @endcan
                            @can('update', $room)
                                <button class="btn btn-ghost btn-xs" wire:click.prevent="updateShowModal({{ $room->id }})">{{ __('Edit') }}</button>
                            @endcan
                            @can('delete', $room)
                                <button class="btn btn-ghost btn-xs" wire:click.prevent="deleteShowModal({{ $room->id }})">{{ __('Delete') }}</button>
                            @endcan
                        </th>
                    </tr>
                </tbody>
                @endforeach
              <!-- foot -->
              <tfoot>
                <tr>
                    <th>{{ __('Název místnosti') }}</th>
                    <th>{{ __('Teplota') }}</th>
                    <th>{{ __('Vlhkost') }}</th>
                    <th>&nbsp;</th>
                  </tr>
              </tfoot>

            </table>
          </div>
    </div>

    @if ($rooms->whereNotNull('deleted_at')->count() > 0)
        <div class="content-window">
            <div class="content-window-header">{{ __('Koš místností') }}</div>
            @if ($rooms->whereNotNull('deleted_at')->count() > 0)
                <div class="overflow-x-auto w-full">
                    <table class="table w-full">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>{{ __('Název místnosti') }}</th>
                            <th>&nbsp;</th>
                          </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms->whereNotNull('deleted_at') as $room)
                            <!-- row 1 -->
                            <tr class="hover">
                                <th>
                                    <div class="font-bold">{{ $room->getName() }}</div>
                                </th>
                                <th>
                                    @can('restore', $model = $room)
                                        <button class="btn btn-ghost btn-xs" wire:click.prevent="restoreShowModal({{ $room->id }})">{{ __('Restore') }}</button>
                                    @endcan
                                    @can('forceDelete', $model = $room)
                                        <button class="btn btn-ghost btn-xs" wire:click.prevent="forceDeleteShowModal({{ $room->id }})">{{ __('Force delete') }}</button>
                                    @endcan
                                </th>
                            </tr>
                        </tbody>
                        @endforeach
                    <!-- foot -->
                    <tfoot>
                        <tr>
                            <th>{{ __('Název místnosti') }}</th>
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
        {{-- The Set Temp Modal --}}
        <x-dialog-modal wire:model="modalSetTempVisible">
            <x-slot name="title">
                <h1>
                    {{ __('Nastavit teplotu v místnosti') }} <strong>{{ $this->modalItem->getName() }}</strong> @if (config('custom.show_floors')) <span class="text-base-content/80 text-sm">{{ $this->modalItem->getFloor() }}</span> @endif
                </h1>
            </x-slot>

            <x-slot name="content">
                <x-validation-errors class="mb-4" />

                <form wire:submit.prevent="setTemp">

                    <div class="grid grid-cols-1 gap-2">
                        <input type="range" min="18" max="24" value="{{ $setTemp }}" wire:model="setTemp" class="range range-primary" step="0.5" />
                        <div class="w-full flex justify-between text-xs px-2">
                        <span>18 °C</span>
                        <span class="text-base font-bold">{{ $setTemp }} °C</span>
                        <span>24 °C</span>
                        </div>
                    </div>
                </form>
            </x-slot>

            <x-slot name="footer">
                <div class="flex gap-2">
                    <button class="btn btn-ghost btn-sm" wire:click="$toggle('modalSetTempVisible')" wire:loading.attr="disabled">
                        {{ __('Zavřít') }}
                    </button>
                    <x-button class="btn-sm ml-2" wire:click="setTemp" wire:loading.attr="disabled">
                        {{ __('Nastavit teplotu') }}
                    </x-button>
                </div>
            </x-slot>

        </x-dialog-modal>

        {{-- The Delete Modal --}}
        <x-confirmation-modal wire:model="modalConfirmDeleteVisible">
            <x-slot name="title">
                {{ __('Odstranit místnost') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('Jsi si jistý? Opravdu si přeješ odstranit místnost') }} <strong>{{ $this->modalItem->getName() }}</strong>?</p>
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

        {{-- The Update Modal --}}
        <x-dialog-modal wire:model="modalUpdateVisible">
            <x-slot name="title">
                <h1>
                    {{ __('Náhled místnosti') }}
                </h1>
            </x-slot>

            <x-slot name="content">
                <x-validation-errors class="mb-4" />

                <form wire:submit.prevent="create">

                    <div class="grid grid-cols-1 gap-2">
                        <div class="form-control">
                            <x-label for="name" value="{{ __('Název místnosti') }}" required />
                            <x-input id="name" class="w-full {{ $errors->has('name') ? 'input-error' : '' }} input-sm" type="text" :value="old('name')" wire:model="name" placeholder="" required autofocus />
                            <x-input-error for="name" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <div class="form-control">
                            <x-label for="floor" value="{{ __('Patro') }}" required />
                            <x-input id="floor" class="w-full {{ $errors->has('floor') ? 'input-error' : '' }} input-sm" type="number" :value="old('floor')" wire:model="floor" placeholder="" required />
                            <x-input-error for="floor" />
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
                        {{ __('Upravit místnost') }}
                    </x-button>
                </div>
            </x-slot>
        </x-dialog-modal>

    @endif

    {{-- The Create Modal --}}
    <x-dialog-modal wire:model="modalCreateVisible">
        <x-slot name="title">
            <h1>
                {{ __('Přidat místnost') }}
            </h1>
        </x-slot>

        <x-slot name="content">
            <x-validation-errors class="mb-4" />

            <form wire:submit.prevent="create">

                <div class="grid grid-cols-1 gap-2">
                    <div class="form-control">
                        <x-label for="name" value="{{ __('Název místnosti') }}" required />
                        <x-input id="name" class="w-full {{ $errors->has('name') ? 'input-error' : '' }} input-sm" type="text" :value="old('name')" wire:model="name" placeholder="" required autofocus />
                        <x-input-error for="name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    <div class="form-control">
                        <x-label for="floor" value="{{ __('Patro') }}" required />
                        <x-input id="floor" class="w-full {{ $errors->has('floor') ? 'input-error' : '' }} input-sm" type="number" :value="old('floor')" wire:model="floor" placeholder="" required />
                        <x-input-error for="floor" />
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
                    {{ __('Přidat místnost') }}
                </x-button>
            </div>
        </x-slot>

    </x-dialog-modal>

    @push('scripts')
    <script>
        const chart = new Chart(
            document.getElementById('graph'), {
                type: 'line',
                data: {
                    labels: ['Monday', 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' , 'Sunday '],
                    datasets: [2112, 2343, 2545, 3423, 2365, 1985, 987]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            }
        );
        Livewire.on('updateChart', data => {
            chart.data = data;
            chart.update();
        });
    </script>
@endpush

</div>
