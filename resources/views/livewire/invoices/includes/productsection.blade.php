<div>
    <table class="w-max border-collapse border-2 text-sm text-left text-gray-900 dark:text-gray-400 table-auto">
        <thead class="text-sm text-gray-800 uppercase  bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
               
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center block">
                    Cód.
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Producto
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Cant.
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Und.
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Precio
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Desc. (%)
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Imp.
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Total
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    <span class="sr-only">Acción</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class=" border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                <td class=" pt-0 border-gray-200 border">
                    <div class="w-[3.5rem]">
                        <x-base-input placeholder="Cód." class=" border-none" type="number"
                            wire:model.lazy="product_code" id="codeInput" label=""
                            wire:keydown.enter="$emit('focusCant')">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border  max-w-[12rem]">
                    <div class="w-48 max-w-[12rem] px-1">
                        <x-datalist value="{{ $product_name }}" class="border-none h-full" :inputId="'pr_name'"
                            model="product_name" type="search" placeholder="Producto" listName="pr_code_name"
                            wire:keydown.enter.prevent="$emit('focusCant')">
                            @foreach ($products as $index => $prod)
                                <option class="bg-gray-200 " value="{{ $index }} {{ $prod }}"
                                    data-value="{{ $index }}">
                                </option>
                            @endforeach
                        </x-datalist>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">

                        <x-base-input id="cant" class="uppercase border-none text-center bg-transparent " type="number"
                            placeholder="Cant." wire:keydown.enter="tryAddItems" wire:model.lazy="cant" label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-[3.5rem]">
                        <x-base-select id="unit_id" class="uppercase border-none text-center bg-transparent "
                            wire:model="unit_id" label="">
                            @if ($product)
                                @foreach ($product['units'] as $unit)
                                    <option value="{{ $unit['pivot']['id'] }}">
                                        {{ $unit['symbol'] }}
                                    </option>
                                @endforeach
                            @endif
                        </x-base-select>

                    </div>
                </td>

                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent" type="number"
                            status="{{ auth()->user()->hasPermissionTo('Asignar Precios')? '': 'disabled' }}"
                            placeholder="Precio" wire:model.lazy="price" id="pr_price" wire:keydown.enter="tryAddItems"  label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " type="number"
                            status="{{ auth()->user()->hasPermissionTo('Aplicar Descuentos')? '': 'disabled' }}"
                            placeholder="Desc." wire:model="discount" wire:keydown.enter="tryAddItems" id="pr_discount"
                            label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">
                        <x-base-input class="uppercase border-none text-center bg-transparent p-0" disabled
                            placeholder="Tax" wire:model="taxTotal" label="" id="pr_tax">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " disabled
                            placeholder="Total" wire:model="total" label="" id="pr_total">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-10 pt-2">
                        <x-button class="bg-transparent p-2 text-gray-800 " wire:click="tryAddItems">
                            <span class="fas fa-plus text-lg"></span>
                        </x-button>
                    </div>
                </td>
            </tr>
            @if (count($details))
                <tr>
                    <td colspan="9" class="py-4 border border-transparent border-b-gray-200"></td>
                </tr>
                @foreach (collect($details) as $det)
                    <tr
                        class="bg-gray-100 odd:bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600   text-sm">
                        <td class="px-2   border border-gray-200 text-right ">
                            {{ $det['product_code'] }}
                        </td>
                        <td class="px-2    border border-gray-200 text-right ">
                            <div class="w-48 max-w-[12rem] overflow-hidden overflow-ellipsis whitespace-nowrap">
                                {{ $det['product_name'] }}
                            </div>
                        </td>
                        <td class="px-2   border border-gray-200 text-right ">
                            {{ formatNumber($det['cant']) }}
                        </td>
                        <td class="px-2   border border-gray-200 text-right ">
                            {{ $det['unit_name'] }}
                        </td>

                        <td class="px-2   border border-gray-200 text-right">
                            {{ '$' . formatNumber($det['price']) }}
                        </td>
                        <td class="px-2   border border-gray-200 text-right">
                            {{ formatNumber($det['discount_rate'] * 100) . '%' }}
                        </td>
                        <td class="px-2   border border-gray-200 text-right">
                            ${{ formatNumber($det['taxTotal']) }}
                        </td>
                        <td class="px-2   border border-gray-200 font-bold text-right ">
                            {{ '$' . formatNumber($det['total']) }}
                        </td>
                        <td class="px-2   border border-gray-200  ">
                            <div class=" flex items-center space-x-4  p-2 bg-gray-200" >
                               {{--  <span wire:click="removeItem({{ $det['id'] }})"
                                    class="  fas fa-trash cursor-pointer text-red-600"></span> --}}
                                <span wire:click="editItem({{ $det['id'] }})"
                                    class="  fas fa-pen cursor-pointer text-green-600"></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr
                    class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 uppercase  text-sm">
                    <td class="px-2 py-4   border-gray-200 text-right ">
                    </td>
                    <td class="px-2 py-4   border-gray-200 text-right ">
                    </td>
                    <td class="px-2 py-4   border-gray-200 text-right ">
                    </td>
                    <td class="px-2 py-4   border-gray-200 text-right ">
                    </td>

                    <td class="px-2 py-4   border-gray-200  text-right">

                    </td>
                    <td class="px-2 py-4   border-gray-200  text-right">

                    </td>
                    <td class="px-2 py-4   border-gray-200 font-bold text-right">
                        ${{ formatNumber(array_sum(array_column($details, 'taxTotal'))) }}
                    </td>
                    <td class="px-2 py-4   border-gray-200 font-bold text-right ">
                        ${{ formatNumber(array_sum(array_column($details, 'total'))) }}
                    </td>
                    <td class="px-2 py-4   border-gray-200 text-right ">
                    </td>
                </tr>
            @endif
    </table>
    <div class="p-2 flex justify-end">
        @if ($product)
            <small>Disp.: {{ formatNumber($this->stock) }}</small>
        @endif
    </div>
    @push('js')
        <script>
            Livewire.on('focusCode', function() {
                $('#codeInput').focus();
            })
            Livewire.on('focusCant', function() {
                $('#cant').focus();
            })
            $(document).ready(function() {
                // Enable Keyboard Input
                document.addEventListener("keydown", key, false);

                function key(e) {
                    code = e.key || e.which;
                    if (code == 'F2') {
                        $('#btntrySendInvoice').click();
                    }
                }
            })
        </script>
    @endpush
</div>
