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
                    <span class="sr-only">Action</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                class=" border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
               
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-[3.5rem]">
                        <x-base-input placeholder="Cód." class=" border-none" type="number"
                            wire:model.lazy="product_code" id="code" label=""  wire:keydown.enter="$emit('focusCant')">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-48">
                        <x-base-input id="pr_name"
                        class="uppercase border-none text-center bg-transparent " disabled placeholder="Nombre del producto"
                        wire:model="product_name" label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">
                        <x-tooltip id="ttStock">Disp.: {{formatNumber($this->stock)}}</x-tooltip>
                        <x-base-input class="uppercase border-none text-center bg-transparent " type="number" placeholder="Cant." wire:model.lazy="cant" wire:keydown.enter="addItems"  id="cant"
                        data-tooltip-target="ttStock" data-tooltip-style="light"
                            label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-[3.5rem]">
                        <x-base-select id="unit_id" class="uppercase border-none text-center bg-transparent " wire:model="unit_id" label="">
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
                        <x-base-input class="uppercase border-none text-center bg-transparent " disabled placeholder="Precio" wire:model="price" id="pr_price" label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " type="number" placeholder="Desc." wire:model="discount" wire:keydown.enter="addItems" id="pr_discount"
                            label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">
                        <x-base-input class="uppercase border-none text-center bg-transparent p-0" disabled placeholder="Tax" wire:model="taxTotal" label="" id="pr_tax">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " disabled placeholder="Total" wire:model="total" label="" id="pr_total">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-10 pt-2">
                        <x-button class="bg-transparent p-2 text-gray-800 " wire:click="addItems">
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
                        class="bg-gray-100 odd:bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 uppercase  text-sm">
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            {{ $det['product_code'] }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            {{ $det['product_name'] }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            {{ formatNumber($det['cant']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            {{ $det['unit_name'] }}
                        </td>

                        <td class="px-2  text-base border border-gray-200 text-right">
                            {{ '$' . formatNumber($det['price']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right">
                            {{ formatNumber($det['discount_rate'] * 100) . '%' }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right">
                            ${{ formatNumber($det['taxTotal']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 font-bold text-right ">
                            {{ '$' . formatNumber($det['total']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            <div class="w-10 pt-2 flex items-center justify-center">
                                <span wire:click="removeItem({{ $det['id'] }})"
                                    class=" text-sm fas fa-trash text-red-600"></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr
                class="bg-slate-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 uppercase  text-sm">
                <td class="px-2 py-4 text-base  border-gray-200 text-right ">
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 text-right ">
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 text-right ">
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 text-right ">
                </td>

                <td class="px-2 py-4 text-base  border-gray-200  text-right">
                   
                </td>
                <td class="px-2 py-4 text-base  border-gray-200  text-right">
                    
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 font-bold text-right">
                    ${{ formatNumber(array_sum(array_column($details,'taxTotal'))) }}
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 font-bold text-right ">
                    ${{ formatNumber(array_sum(array_column($details,'total'))) }}
                </td>
                <td class="px-2 py-4 text-base  border-gray-200 text-right ">
                </td>
            </tr>
            @endif
    </table>
    @push('js')
        <script>
            Livewire.on('focusCode', function(){
                $('#code').focus();
            })
            Livewire.on('focusCant', function(){
                $('#cant').focus();
            })
        </script>
    @endpush
</div>
