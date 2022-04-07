<div>
    <x-modal maxWidth="max-w-4xl">
        <x-slot name="title">
            <span>Asignar Permisos</span>
        </x-slot>
        <x-slot name="button">
            <span class="far fa-shield-check fa-xl text-yellow-600" data-tooltip-target="assignPermission{{$user->id}}"
                data-tooltip-style="light"></span>
                <x-tooltip id="assignPermission{{$user->id}}">Gestionar permisos</x-tooltip>
        </x-slot>
            <div class="">
                @can('Asignar Permisos')
            <div class=" p-8" x-data="{ selectAll: false, open: false }">
                <h1 class="text-center uppercase font-bold text-xl mt-4 mb-6 flex justify-between items-center cursor-pointer"
                    @click="open = !open">
                    <span>Gestionar permisos de {{ $user->name }}</span>
                    <x-tooltip id="managePermissions">
                        Ver/Cambiar permisos
                    </x-tooltip>
                    <span data-tooltip-target="managePermissions" data-tooltip-style="light" class="far fa-pen-square"
                        x-bind:class=" open?' text-green-600':'text-yellow-600'"></span>
                </h1>
                <form action="" wire:submit.prevent="changePermissions" x-show="open" x-transition>
                    <div class="grid grid-cols-4 gap-6">
                        @foreach ($permissions as $id => $perm)
                            @can($perm)
                                <div class="flex space-x-2 items-center mb-4 ">
                                    <input type="checkbox" name="permissions" id="permi{{ $id }}"
                                        wire:loading.attr="disabled" wire:target="permissionsSelected"
                                        wire:model="permissionsSelected" value="{{ $perm }}"
                                        class="w-6 h-6 text-blue-600 rounded-full bg-gray-100  border-gray-300 focus:ring-blue-500  cursor-pointer disabled:cursor-default disabled:ring-gray-500 disabled:text-gray-500"
                                        x-bind:checked="selectAll"
                                        {{in_array($perm, $rolePermissions)?'disabled':''}}
                                        >
                                    <label for="perm{{ $id }}"
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
                                label="{{ $selectAll ? 'Deseleccionar todos' : 'Seleccionar todos' }}" id="selectAll{{$user->id}}"
                                wire:click="$set('selectAll',{{ !$selectAll }})"></x-toggle>
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
    </x-modal>

</div>
