    <div class="overflow-hidden w-full max-w-6xl min mx-auto shadow-md sm:rounded-lg  pb-3" style="min-height: 30rem">
        <h1 class="text-center font-bold uppercase text-xl mb-4 mt-0    ">Listado de usuarios registrados</h1>
        <x-loading wire:loading.class.remove="hidden" wire:target="deleteUser"></x-loading>
        <div class="flex justify-between p-3">
            <div class="flex items-center space-x-4 text-lg">
                <span>Ver</span>
                <span class="w-8">
                    <x-input label="" class="text-center" type="number" wire:model.lazy="perPage"></x-input>
                </span>
                <span class="hidden lg:block">de {{ $users->total() }}</span>
            </div>
            <div class="max-w-xs w-full ml-4">
                <x-input type="search" wire:model="search" id="searchUser" label="Buscar"></x-input>
            </div>
        </div>
        <div class="max-w-6xl overflow-x-auto">
            <table class=" ">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="py-3 px-6 text-lg font-bold tracking-wider text-left text-gray-900 uppercase dark:text-gray-400 ">
                            <div wire:click.revent="sortBy('name')"
                                class="hover:text-blue-400 cursor-pointer select-none w-full flex justify-between items-center space-x-2">
                                <span>Nombre</span>
                                <span
                                    class="{{ $sortField == 'name' ? ($sortAsc ? 'fas fa-angle-up' : 'fas fa-angle-down') : 'fas fa-angle-down' }}"></span>
                            </div>
                        </th>
                        <th scope="col"
                            class="py-3 px-6 text-lg font-bold tracking-wider text-left text-gray-900 uppercase dark:text-gray-400 ">
                            <div wire:click.prevent="sortBy('lastname')"
                                class="hover:text-blue-400 cursor-pointer select-none w-full flex justify-between items-center space-x-2">
                                <span>Apellidos</span>
                                <span
                                    class="{{ $sortField == 'lastname' ? ($sortAsc ? 'fas fa-angle-up' : 'fas fa-angle-down') : 'fas fa-angle-down' }}"></span>
                            </div>
                        </th>
                        <th scope="col"
                            class="py-3 px-6 text-lg font-bold tracking-wider text-left text-gray-900 uppercase dark:text-gray-400 w-2/5">
                            <div wire:click.prevent="sortBy('email')"
                                class="hover:text-blue-400 cursor-pointer select-none max-w-xs w-full flex justify-between items-center">
                                <span>Correo</span>
                                <span
                                    class="{{ $sortField == 'email' ? ($sortAsc ? 'fas fa-angle-up' : 'fas fa-angle-down') : 'fas fa-angle-down' }}"></span>
                            </div>
                        </th>
                        <th scope="col"
                            class="py-3 px-6 text-lg font-bold tracking-wider text-left text-gray-900 uppercase dark:text-gray-400 ">
                            <div wire:click.prevent="sortBy('phone')"
                                class="hover:text-blue-400 cursor-pointer select-none w-full flex justify-between items-center space-x-2">
                                <span>Teléfono</span>
                                <span
                                    class="{{ $sortField == 'phone' ? ($sortAsc ? 'fas fa-angle-up' : 'fas fa-angle-down') : 'fas fa-angle-down' }}"></span>
                            </div>
                        </th>
                        <th scope="col" class="relative py-3 px-6 tracking-wider uppercase text-lg bg-gray-100">
                            <span class="">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->count())
                        @foreach ($users as $user)
                            <tr
                                class="border-b odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 dark:border-gray-600">
                                <td
                                    class="py-2 pl-3 text-lg font-medium text-gray-900  whitespace-normal w-full dark:text-white">
                                    <div class="flex space-x-2 items-center">
                                        <div class="w-8 h-8 bg-cover bg-center rounded-full"
                                            style="background-image: url({{ $user->avatar }}); min-width:2rem; min-height:2rem">

                                        </div>
                                        <span class=" whitespace-normal">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-6 text-lg text-gray-500 whitespace-normal dark:text-gray-400">
                                    {{ $user->lastname }}
                                </td>
                                <td class="py-2 px-6 text-lg text-gray-500 whitespace-normal dark:text-gray-400">
                                    {{ $user->email }}
                                </td>
                                <td class="py-2 px-6 text-lg text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ $user->phone }}
                                </td>
                                <td class="py-2 px-6 text-lg " wire:loading.class="bg-red-100"
                                    wire:target="deleteUser({{ $user->id }})">
                                    @if (!$user->hasRole('Super Admin'))
                                        <div class="flex flex-row items-center space-x-2">
                                            <div>
                                                <livewire:users.edit-user :user="$user" :wire:key="uniqid()" />
                                            </div>
                                            
                                            @if (!$user->hasRole('Administrador') || auth()->user()->hasRole('Super Admin'))
                                            @can('Asignar Permisos')
                                                <div>
                                                    <livewire:users.assign-permission :user="$user"
                                                        :wire:key="uniqid().'id'" />
                                                </div>
                                            @endcan
                                                @can('Borrar Usuarios')
                                                <div>
                                                    <livewire:general.delete-model :model="$user" event="reloadUsers"
                                                        title="Usuario" permission="Borrar usuarios"
                                                        :wire:key="uniqid()" />
                                                </div>
                                                @endcan
                                            @endif

                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                        @if ($users->hasPages())
                            <tr>
                                <td colspan="5" class="p-3">
                                    {{ $users->links('vendor.livewire.tailwind') }}
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="5">
                                <h1 class="uppercase font-bold text-2xl text-center py-4">No se encontró ningún usuario
                                </h1>
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>

        </div>

    </div>
