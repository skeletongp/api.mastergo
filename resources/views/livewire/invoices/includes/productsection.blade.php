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
                    Medida
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Precio
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Desc. (%)
                </th>
                <th scope="col" class="px-2 py-3 border border-gray-200 text-center">
                    Tax
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
                    <div class="w-24">
                        <x-base-input placeholder="Código" class=" border-none" type="number"
                            wire:model.lazy="product_code" label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-48">
                        <x-base-input class="uppercase border-none text-center bg-transparent " readonly placeholder="Nombre del producto"
                            wire:model="product.name" label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">
                        <x-base-input class="uppercase border-none text-center bg-transparent " type="number" placeholder="Cant." wire:model.lazy="cant"
                            label=""></x-base-input>
                    </div>
                </td>

                <td class=" pt-0 border-gray-200 border">
                    <div class="w-32">
                        <x-base-select id="unit_id" class="uppercase border-none text-center bg-transparent" wire:model="unit_id" label="">
                            @if ($product)

                                @foreach ($product['units'] as $unit)
                                    <option value="{{ $unit['pivot']['id'] }}">
                                        {{ $unit['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </x-base-select>
                    </div>
                </td>

                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " readonly placeholder="Precio" wire:model="price" label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " type="number" placeholder="Desc." wire:model="discount"
                            label=""></x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-16">
                        <x-base-input class="uppercase border-none text-center bg-transparent p-0" readonly placeholder="Tax" wire:model="totalTax" label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-24">
                        <x-base-input class="uppercase border-none text-center bg-transparent " readonly placeholder="Total" wire:model="total" label="">
                        </x-base-input>
                    </div>
                </td>
                <td class=" pt-0 border-gray-200 border">
                    <div class="w-12 pt-2">
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

                        <td class="px-2  text-base border border-gray-200 text-right text-right">
                            {{ '$' . formatNumber($det['price']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right text-right">
                            {{ formatNumber($det['discount_rate'] * 100) . '%' }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right text-right">
                            ${{ formatNumber($det['totalTax']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right font-bold text-right ">
                            {{ '$' . formatNumber($det['total']) }}
                        </td>
                        <td class="px-2  text-base border border-gray-200 text-right ">
                            <div class="w-12 pt-2 flex items-center justify-center">
                                <span wire:click="removeItem({{ $det['id'] }})"
                                    class=" text-sm fas fa-trash text-red-600"></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
    </table>
    {{-- {{dd($details)}} --}}
</div>
