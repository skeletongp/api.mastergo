<div class="max-w-7xl shadow-xl p-4">
    <div class="flex space-x-4 items-start relative">
        <div class="w-full">
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
                    
                </div>
            </form>
            <div class="mt-4">
                @if (count($productAdded))
                    <div class="pr-4 " x-data="{ open: false }" x-cloak>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                                <x-toggle label="Registrar gasto" id="setCost" wire:model="setCost"></x-toggle>
                            </div>
                           
                        </div>

                    </div>
                @endif
            </div>
        </div>
        <div class="w-full">
            @include('livewire.products.includes.sumproductmoney')
        </div>
    </div>
    @push('js')
        <script>
            Livewire.on('printProvision', function(provision) {
                printProvision(provision);
            })

            function printProvision(provision) {
                console.log(provision);
            }
        </script>
    @endpush
</div>
