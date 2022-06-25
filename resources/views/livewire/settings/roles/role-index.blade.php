<div class="px-4 mt-8" x-data="{ open: false }" x-cloak>
    <h1 class="uppercase text-xl text-center font-bold mb-4 mt-8">Roles del sistema</h1>

    <div class=" overflow-x-auto shadow-md sm:rounded-lg" x-data="{ selectAll: false, open: false }">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Rol
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Permisos
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        <span class="sr-only">
                            Acciones
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($roles as $rol)
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                        <th scope="row"
                            class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer"
                            @click=" open = true" wire:click="$set('role_name','{{ $rol->name }}')">
                            {{ preg_replace('/[0-9]+/', '', $rol->name) }}
                        </th>
                        <td class="px-6 py-2  cursor-pointer" wire:click="$set('role_name','{{ $rol->name }}')">
                            {{ $rol->permissions->count() . '/' . $permissions->count() }} Permisos
                        </td>
                        <td class="px-6 py-2">
                            <div class="flex space-x-4 w-max mx-auto ">
                                @if ($rol->name !== 'Super Admin' && $rol->name !== 'Administrador')
                                    <button>
                                        <span class="far fa-trash text-red-400"
                                            wire:click="confirm('¿Desea eliminar este rol?', 'deleteRole', '{{ $rol->name }}', 'Borrar Roles')">
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td >
                            <button @click="open = !open">
                                <x-tooltip id="managePermissions">
                                    Ver/Cambiar permisos
                                </x-tooltip>
                                <span data-tooltip-target="managePermissions" class="far fa-pen-square"
                                    x-bind:class=" open ? ' text-green-600' : 'text-yellow-600'"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        @can('Asignar Permisos')
            @if ($role_name)
                <div class=" p-8">
                    <h1 class="text-center uppercase font-bold text-xl mt-4 mb-6 flex justify-between items-center cursor-pointer"
                        @click="open = !open">
                        <span>Gestionar permisos de {{ preg_replace('/[0-9]+/', '', $role_name) }}</span>
                       
                    </h1>
                    <form action="" wire:submit.prevent="changePermissions" x-show="open" x-transition>
                        <div class="grid grid-cols-4 gap-6">
                            @foreach ($permissions->sort() as $id => $perm)
                                @can($perm)
                                    <div class="flex space-x-2 items-center mb-4 p-2 shadow-lg">
                                        <input type="checkbox" name="permissions" id="perm{{ $id }}"
                                            wire:loading.attr="disabled" wire:target="permissionsSelected"
                                            wire:model="permissionsSelected" value="{{ $perm }}"
                                            class="w-6 h-6 text-blue-600 rounded-full bg-gray-100  border-gray-300 focus:ring-blue-500  cursor-pointer disabled:cursor-default"
                                            x-bind:checked="selectAll">
                                        <label for="perm{{ $id }}"
                                            class="ml-3 text-sm font-medium text-gray-900 text-center dark:text-gray-300 cursor-pointer">
                                            {{ $perm }}
                                        </label>
                                        @can('Borrar Permisos')
                                            <span class="fas fa-times text-red-500 cursor-pointer"
                                                onclick="return confirm('¿Eliminar permiso?')||event.stopImmediatePropagation();"
                                                wire:click="deletePermission('{{ $perm }}')"></span>
                                        @endcan
                                    </div>
                                @endcan
                            @endforeach

                        </div>

                        <div class="flex  justify-between pt-8">
                            <div class="">
                                <x-toggle checked="{{ $selectAll }}"
                                    label="{{ $selectAll ? 'Deseleccionar todos' : 'Seleccionar todos' }}" id="selectAll"
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
            @endif
        @endcan
    </div>

</div>
