<form action="" id="form2" wire:submit.prevent="addUnit" class="mt-4 flex space-x-4 max-w-5xl mx-auto">
    <div class="w-1/2">
        <div class="flex space-x-4 items-end">
            <div class="w-full">
                <label for="unit_id">Precio por Medida</label>
                <x-select name="unit_id" wire:model.defer="unit_id" id="unit_id">
                    <option value=""></option>
                    @foreach ($units as $id => $unit)
                        <option value="{{ $id }}">{{ $unit }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="unit_id">Requerido</x-input-error>
            </div>
            <div>
                <x-base-input type="number" id="unit_min" wire:model.defer="unit_min" label="Cant. Min."></x-base-input>
                <x-input-error for="unit_min">Requerido</x-input-error>
            </div>


        </div>
        <div class="flex space-x-4 items-start">
            <div class="pt-4 w-1/4">
                <x-base-input type="number" label="Costo" type="number" step="any" min="0" id="unit_cost" name="unit_cost"
                    wire:model.lazy="unit_cost"></x-base-input>
                <x-input-error for="unit_cost">Verifique el costo</x-input-error>
            </div>
            <div class="pt-4 w-1/4">
                <x-base-input type="number" label="Precio Mayor" type="number" step="any" min="0" id="unit_price_mayor"
                    name="unit_price_mayor" wire:model.lazy="unit_price_mayor"></x-base-input>
                <x-input-error for="unit_price_mayor">Verifique el precio</x-input-error>
            </div>
            <div class="pt-4 w-1/4">
                <x-base-input type="number" label="Precio Detalle" type="number" step="any" min="0" id="unit_price_menor"
                    name="unit_price_menor" wire:model.lazy="unit_price_menor"></x-base-input>
                <x-input-error for="unit_price_menor">Verifique el precio</x-input-error>
            </div>
            <div class="pt-4 w-1/4">
                <x-base-input type="number" label="Margin (%)" type="number" step="any" min="0" id="unit_margin" name="unit_margin"
                    wire:model.lazy="unit_margin"></x-base-input>
                <x-input-error for="unit_margin">Verifique el margen</x-input-error>
            </div>
        </div>
        <div class="p-2 pr-0 flex justify-end">
            <x-button class=" uppercase disabled:bg-gray-200 text-xs" wire:loading.attr='disabled' form="form2">
                <div class="animate-spin mr-2" wire:loading wire:target="addUnit">
                    <span class="fa fa-spinner ">
                    </span>
                </div>
                Añadir
            </x-button>
        </div>
        <x-input-error for="unitSelected">Añada por lo menos un precio por medida</x-input-error>
    </div>
    <div class="mt-4 w-1/2">
        @if (count($unitSelected))
            <div class="" x-data="{ open: false }" x-cloak>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class=" text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Unidad
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Precio
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Costo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Margen
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <span class="sr-only">
                                        Acciones
                                    </span>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="text-base">
                            @foreach ($unitSelected as $uniSel)
                                <tr
                                    class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                    <th scope="row"
                                        class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                        {{ $units[$uniSel['unit_id']] }}
                                    </th>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ formatNumber($uniSel['price_menor']) }}
                                    </td>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ formatNumber($uniSel['cost']) }}
                                    </td>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ formatNumber($uniSel['margin'] * 100) }}%
                                    </td>
                                    <td class="px-6 py-2">
                                        <div class="flex space-x-4 w-max mx-auto cursor-pointer ">
                                            <span class="far fa-trash-alt"
                                                wire:click="remove({{ $uniSel['id'] }})"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>

            </div>
        @endif
    </div>
</form>
<div class="bottom-0 absolute w-full">
    <div class="p-2 pr-0 flex justify-between w-full">
        <x-button wire:click="$set('activeTab','infoproduct')" disabled="{{ $activeTab == 'infoproduct' }}"
            class="uppercase disabled:bg-gray-200 text-xs">
            Anterior
        </x-button>
        <x-button wire:click="createProduct" class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
            wire:loading.attr='disabled'>
            <div class="animate-spin mr-2" wire:loading wire:target="createProduct">
                <span class="fa fa-spinner ">
                </span>
            </div>
            <span>Guardar</span>
        </x-button>
    </div>
</div>
