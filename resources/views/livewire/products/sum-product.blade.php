<div class="max-w-7xl shadow-xl p-4">
    <div class="flex flex-row space-x-4  items-start relative">
        <div class="w-full min-w-[40rem]">
            <form action="" wire:submit.prevent="addProduct">
                <div class="flex space-x-2 items-start pt-12 relative">
                    <div class="w-full max-w-[12rem] ">
                        <x-datalist label="Nombre de producto" model="form.product_id" inputId="producto_id" listName="productIdList">
                            <option value=""></option>
                            @foreach ($products as $id => $product)
                                <option data-value="{{ $id }}" value="{{ $product }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="form.product_id"></x-input-error>
                    </div>
                    @if (array_key_exists('product_id', $form))
                        <div class=" w-2/6 px-2">
                            <x-base-select wire:model="form.unit" label="Medida" id="medida_id">
                                <option value=""></option>
                                @foreach ($units as $id => $unit)
                                    <option value="{{ $id }}">{{ $unit }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="form.unit"></x-input-error>
                        </div>
                    @endif
                    @if (array_key_exists('unit', $form) && $form['unit'])
                        <div class=" w-1/6">
                            <x-base-input wire:loading.attr="disabled" wire:keydown.enter="addProduct" type="number"
                                label="Costo" id="form.cost" wire:model.defer="form.cost" />
                            <x-input-error for="form.cost"></x-input-error>
                        </div>
                        <div class=" w-1/6">
                            <x-base-input wire:keydown.enter="addProduct" type="number" label="Cantidad" id="form.cant"
                                wire:model.defer="form.cant" />
                            <x-input-error for="form.cant"></x-input-error>
                        </div>
                    @endif

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
                                        <th scope="col" class="px-4 py-3">
                                            Producto
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Medida
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Cost.
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Cant.
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-center">
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
                                                class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                                {{ $added['product_name'] }}
                                            </th>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ $added['unit_name']}}
                                            </td>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ formatNumber($added['cost']) }}
                                            </td>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ formatNumber($added['cant']) }}
                                            </td>

                                            <td class="px-4 py-2">
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
                        <div class="flex items-center justify-between mt-4">
                            <b>Total:</b> {{ formatNumber($total) }}
                            @can('Registrar Asientos')
                                <div class="mr-6">
                                    <x-toggle label="Registrar gasto" id="setCost" wire:model="setCost"></x-toggle>
                                </div>
                            @endcan

                        </div>

                    </div>
                @endif
            </div>
        </div>
        @if (count($productAdded))
            <div class="w-full">
                @include('livewire.products.includes.sumproductmoney')
            </div>
          
        @endif
    </div>
    <div class="opacity-0">
        @livewire('provisions.print-provision', ['provision_code' => 0], key(uniqid()))
       </div>
</div>
