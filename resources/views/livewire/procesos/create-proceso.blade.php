<div class="w-full">
    <h1 class="text-center font-bold uppercase my-6 text-xl">Registrar proceso de producción</h1>
    <div class="max-w-xl space-y-4 p-4 w-full mx-auto">
        {{-- Proceso --}}
        <form action="" id="fCrearProceso" wire:submit.prevent="createProceso" class="space-y-4 w-full">
            <div class="flex space-x-4 items-end w-full ">
                <div class="w-full">
                    <x-input label="Nombre" wire:model.defer="form.name" id="form.proccess.name"></x-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div>
                    <x-input type="date" label="Fecha Inicio" wire:model.defer="form.start_at"
                        id="form.proccess.start_at"></x-input>
                    <x-input-error for="form.start_at"></x-input-error>
                </div>
            </div>
        </form>

        <div class="flex justify-end mt-4 max-w-xl mx-auto">
            <x-button form="fCrearProceso">
                Registrar
            </x-button>
        </div>


    </div>
    {{-- Requerimientos --}}
    <div class="flex space-x-4 items-start max-w-6xl  mx-auto w-full">
        {{-- Recursos --}}
        <div class="pt-6 w-full border-r pr-4">
            <h1 class="text-center text-xl font-medium my-2">Recursos del proceso</h1>
            <form action="" wire:submit.prevent="addRecurso">
                <div class="flex space-x-4 items-end w-full ">
                    <div class="w-full max-w-xs space-y-2">
                        <label for="frRecurso.id" class="text-xl font-medium ">Recurso requerido</label>
                        <x-select id="frRecurso.id" wire:model="recursoId" required>
                            <option value=""></option>
                            @foreach ($recursos as $recurso)
                                <option value="{{ $recurso->id }}">{{ $recurso->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="fRecursos">Añada un recurso</x-input-error>
                    </div>
                    @if ($selRecurso)
                        <div>
                            <x-input type="number" required min="1" label="Cantidad (máx. {{ $selRecurso->cant }})"
                                wire:model.defer="recursoCant" id="frRecurso.cant" max="{{ $selRecurso->cant }}">
                            </x-input>
                            <x-input-error for="recursoCant"></x-input-error>
                        </div>
                    @endif
                </div>
            </form>
            @if (count($fRecursos))
                <div class=" overflow-x-auto shadow-md sm:rounded-lg" x-data="{ selectAll: false, open: false }">
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

                        </tbody>
                    </table>

                </div>
            @endif
        </div>
        {{-- Productos --}}
        <div class="pt-10 w-full">
            <h1 class="text-center text-xl font-medium my-2">Productos del proceso</h1>
            <form action="" wire:submit.prevent="addProduct">
                <div class="flex space-x-4 items-end w-full ">
                    <div class="w-full max-w-xs space-y-2">
                        <label for="frProduct.id" class="text-xl font-medium ">Producto a generar</label>
                        <x-select id="frProduct.id" wire:model="productId" required>
                            <option value=""></option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="fProducts">Añada un producto</x-input-error>
                    </div>
                    @if ($selProduct)
                        <div class="w-full max-w-xs space-y-2">
                            <label for="frProduct.unit" class="text-xl font-medium ">Medida</label>
                            <x-select id="frProduct.unit" wire:model="productUnit" required>
                                <option value=""></option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="ProductUnit"></x-input-error>
                        </div>
                        <div>
                            <x-input type="number" required min="1" label="Esperado " wire:model.defer="productDue"
                                id="frProduct.due">
                            </x-input>
                            <x-input-error for="productDue"></x-input-error>
                        </div>
                    @endif
                </div>
            </form>
            @if (count($fProducts))
                <div class=" overflow-x-auto shadow-md sm:rounded-lg" x-data="{ selectAll: false, open: false }">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Recurso
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Esperado
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <span class="sr-only">
                                        Acciones
                                    </span>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="text-base">
                            @foreach ($fProducts as $product)
                                <tr
                                    class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                    <th scope="row"
                                        class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                        {{ $product['name'] }}
                                    </th>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ $product['due'].' '.$product['unitname'] }}
                                    </td>
                                    <td class="px-6 py-2">
                                        <div class="flex space-x-4 w-max mx-auto ">
                                            <span class="far fa-trash-alt text-red-500"
                                                wire:click="removeProduct({{ $product['product_id'] }})">
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            @endif
        </div>
    </div>
   
</div>
