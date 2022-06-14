<div class="max-w-4xl shadow-xl p-4">
    <form action="" wire:submit.prevent="addProduct">
        <div class="flex space-x-4 items-start pt-12 relative">
            <div class="w-3/6 select2-div">

                <x-base-select label="Nombre de producto" wire:model="form.product_id" id="producto_id">
                    <option value=""></option>
                    @foreach ($products as $id => $product)
                        <option value="{{ $id }}">{{ $product }}</option>
                    @endforeach
                </x-base-select>
                <x-input-error for="form.product_id"></x-input-error>
            </div>
            <div class=" w-2/6 px-4">
                <x-base-select wire:model.defer="form.unit" label="Medida" id="medida_id">
                    <option value=""></option>
                    @foreach ($units as $id => $unit)
                        <option value="{{ $id }}">{{ $unit }}</option>
                    @endforeach
                </x-base-select>
                <x-input-error for="form.unit"></x-input-error>
            </div>
            <div class=" w-1/6">
                <x-base-input type="number" label="Cantidad" id="form.cant" wire:model.defer="form.cant" />
                <x-input-error for="form.cant"></x-input-error>
            </div>
            
            <div class="absolute top-1 right-1">
                <x-button>
                    <span class="fas fa-plus"></span>
                </x-button>
            </div>
        </div>
    </form>
    <div class="mt-4">
        @if (count($productAdded))
            <div class="pr-4 " x-data="{ open: false }" x-cloak>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Producto
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cantidad
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Medida
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <span class="sr-only">
                                        Acciones
                                    </span>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="text-base">
                            @foreach ($productAdded as $added)
                                <tr
                                    class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                    <th scope="row"
                                        class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                        {{ $added['product_name'] }}
                                    </th>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ formatNumber($added['cant']) }}
                                    </td>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ $units[$added['unit']] }}
                                    </td>

                                    <td class="px-6 py-2">
                                        <div class="flex space-x-4 w-max mx-auto cursor-pointer ">
                                            <span class="far fa-trash-alt"
                                                wire:click="remove({{ $added['id'] }})"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-end mt-4">

                    <div class="mr-6">
                        <x-toggle label="Registrar gasto" id="setCost" wire:model.defer="setCost"></x-toggle>
                    </div>
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled' wire:click.prevent="sumCant">Guardar</x-button>

                </div>
                <div class="flex space-x-4 items-start mt-4">
                    <div class="w-full">
                        <x-base-select label="Proveedor" wire:model.defer="provider_id">
                            <option class="text-gray-300"> Elija un proveedor</option>
                            @foreach (auth()->user()->store->providers as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->fullname }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="provider_id">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select label="Cuenta" wire:model.defer="count_code">
                            <option class="text-gray-300"> Elija una cuenta</option>
                            @foreach ($counts as $code => $count)
                                <option value="{{ $code }}">{{ $count }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="count_code">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                       <x-base-input label="Referencia" placeholder="NCF u otro referencia" wire:model.defer="ref"></x-base-input>
                        <x-input-error for="ref">Campo requerido</x-input-error>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @push('js')

        <script>
            Livewire.on('printProvision', function(provision){
                printProvision(provision);
            })
            function printProvision(provision){
                console.log(provision);
            }
        </script>
    @endpush
</div>
