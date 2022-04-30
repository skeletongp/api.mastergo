<div class="col-span-4 ">
    <div class="py-8">
        @if (count($details))
            <table class="w-full  ">
                <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                            Artículos
                        </th>
                        <td class="px-6  text-xl font-bold text-right">
                            {{ formatNumber(count($details)) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                            Monto
                        </th>
                        <td class="px-6  text-2xl font-bold text-right">
                            ${{ formatNumber(array_sum(array_column($details, 'subtotal'))) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                            Impuestos
                        </th>
                        <td class="px-6  text-2xl font-bold text-right">
                            ${{ formatNumber(array_sum(array_column($details, 'total'))-array_sum(array_column($details, 'subtotal'))) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                            TOTAL
                        </th>
                        <td class="px-6  text-3xl font-bold text-right">
                            ${{ formatNumber(array_sum(array_column($details, 'total'))) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end ">
                @if (count($details))
                    <x-button wire:click.prevent="sendInvoice" id="sendInvoice">
                        <span class="fas fa-save"></span>
                    </x-button>
                @endif
            </div>
            <table class="w-full text-base ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            Producto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left">
                            Cant.
                        </th>
                        <th scope="col" class="px-6 py-3 text-right">
                            Precio
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (array_reverse($details, true) as $det)
                        <tr class="bg-white border-b cursor-pointer uppercase dark:bg-gray-800 dark:border-gray-700 text-lg">
                            <td scope="row" wire:click="setProduct({{$det['product_id']}})" class=" text-left px-2  font-bold text-gray-900 ">
                                <h1 class="overflow-hidden overflow-ellipsis whitespace-nowrap max-w-[15rem]">
                                    {{ $det['product_name'] }}
                                </h1>
                            </td>
                            <td class="px-6   font-bold text-left">
                                <h1> {{ $det['cant'] }}<span
                                        class="text-xs">{{ $det['unit_name'] }}</span></h1>
                            </td>
                            <td class="px-6   font-bold text-right">
                                ${{ formatNumber($det['price']) }}
                            </td>

                            <td class="px-6   font-bold text-left">
                                <span class="fas fa-trash-alt text-red-400 cursor-pointer"
                                    wire:click="removeItem({{ $det['id'] }})"></span>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        @endif
    </div>

</div>
<form onsubmit="setFocused(false)" action="" wire:submit.prevent="setFromScan" class=" opacity-0">
    <x-input tabindex="-1" label="scanned" id="scanned"  wire:model.defer="scanned"></x-input>
</form>