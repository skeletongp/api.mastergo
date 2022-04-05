<div>
    @if ($places->count())
        <h1 class="my-4 text-left uppercase font-bold text-xl">
            Sucursales registradas
        </h1>
    <fieldset>
        <table class="text-sm break-words text-left text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-600 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Responsable
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Teléfono
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        <span class="sr-only">
                            Acciones
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($places as $place)
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                        <th scope="row" wire:click="changePlace({{$place->id}})"
                            class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-normal    cursor-pointer">
                            {{ $place->name }}
                        </th>
                        <th scope="row"
                            class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-normal   cursor-pointer">
                            <div class="w-32 md:w-60 overflow-hidden overflow-ellipsis whitespace-nowrap">
                                {{ optional($place->user)->fullname?:'No Asignado' }}
                            </div>
                        </th>
                        <td class="px-6 py-2  cursor-pointer">
                            {{ $place->phone }}
                        </td>
                        <td class="px-6 py-2">
                            @if ($place->id !==auth()->user()->store->places()->first()->id)
                                <div class="flex space-x-4 w-max mx-auto ">
                                    <livewire:general.delete-model title="registro" permission="Borrar Roles"
                                        :model="$place" event="reloadPlaces" :wire:key="uniqid()" />
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </fieldset>
    @endif

</div>
