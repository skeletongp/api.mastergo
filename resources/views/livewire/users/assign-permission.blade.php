    <div class="">
        @can('Asignar Permisos')
            <div class=" p-8" x-data="{ selectAll: false }">
                <h1
                    class="text-center uppercase font-bold text-xl mt-4 mb-6 flex justify-between items-center cursor-pointer">
                    <span>Gestionar permisos de {{ $user['name'] }}</span>
                    <x-tooltip id="managePermissions">
                        Ver/Cambiar permisos
                    </x-tooltip>

                </h1>
                <form action="" wire:submit.prevent="changePermissions" x-transition>
                    <div class="grid grid-cols-5 gap-2">
                        @php
                            asort($permissions)
                        @endphp
                        @foreach ($permissions as $id => $perm)
                            @can($perm)
                                <div class="flex space-x-2 items-center mb-2 ">
                                    <input type="checkbox" name="permissions" id="permi{{ $id }}"
                                        wire:loading.attr="disabled" wire:target="permissionsSelected"
                                        wire:model="permissionsSelected.{{ $id }}" value="{{ $perm }}"
                                        class="w-6 h-6 text-blue-600 rounded-full bg-gray-100  border-gray-300 focus:ring-blue-500  cursor-pointer "
                                        x-bind:checked="selectAll">
                                    <label for="permi{{ $id }}"
                                        class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                                        {{ $perm }}
                                    </label>

                                </div>
                            @endcan
                        @endforeach

                    </div>

                    <div class="flex  justify-between pt-8">
                        <div class="">
                            <x-toggle checked="{{ $selectAll }}"
                                label="{{ $selectAll ? 'Deseleccionar todos' : 'Seleccionar todos' }}"
                                id="selectAll{{ $user['id'] }}" wire:click="$set('selectAll',{{ !$selectAll }})">
                            </x-toggle>
                        </div>
                        <x-button
                            class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-800 text-xs"
                            wire:loading.attr='disabled'>
                            <div class="animate-spin mr-2" wire:loading>
                                <span class="fa fa-spinner ">
                                </span>
                            </div>
                            <span>Actualizar</span>
                        </x-button>
                    </div>
                </form>
            </div>
        @endcan
    </div>
