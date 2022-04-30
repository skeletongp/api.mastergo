<div class=" w-full space-y-4 shadow-sm">
    <h1 class="text-left uppercase text-xl font-bold my-2">Recursos del proceso</h1>
    <form class="" action="" wire:submit.prevent="addRecurso">
        <div class="flex space-x-4 items-end w-full ">
            <div class="w-full max-w-xs space-y-2">
                <x-select id="frRecurso.id" wire:model="recursoId" required>
                    <option value="">Elija un recurso</option>
                    @foreach ($recursos as $recurso)
                        <option value="{{ $recurso->id }}">{{ $recurso->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <x-input type="number" required min="1" label="Cantidad máx. ({{ optional($selRecurso)->cant }})"
                    wire:model.defer="recursoCant" id="frRecurso.cant" max="{{ optional($selRecurso)->cant }}">
                </x-input>
                <x-input-error for="recursoCant"></x-input-error>
            </div>
            <div>
                <x-button  class="bg-gray-100 ">
                    <span class="far fa-save text-gray-800 text-xl"></span>
                </x-button>
            </div>
        </div>
        <x-input-error for="fRecursos">Añada un recurso</x-input-error>

    </form>
    <div class=" overflow-x-auto shadow-md sm:rounded-lg mt-4" x-data="{ selectAll: false, open: false }">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Recurso
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cantidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        <span class="sr-only">
                            Acciones
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody class="text-base">
                @if (count($fRecursos))
                    @foreach ($fRecursos as $recurso)
                        <tr
                            class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                            <th scope="row"
                                class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                {{ $recurso['name'] }}
                            </th>
                            <td class="px-6 py-2  cursor-pointer">
                                {{ $recurso['cant'] }}
                            </td>
                            <td class="px-6 py-2">
                                <div class="flex space-x-4 w-max mx-auto ">
                                    <span class="far fa-trash-alt text-red-500"
                                        wire:click="removeRecurso({{ $recurso['recurso_id'] }})">
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">
                            <h1 class="uppercase text-center font-bold text-xl py-4">No ha seleccionado ningún recurso a utilizar
                            </h1>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
</div>
