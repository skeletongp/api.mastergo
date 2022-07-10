<div>
    <div class=" overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-900 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Cod.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Producto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cant.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Und.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Desc.
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total.
                    </th>
                 {{--    @if (\Carbon\Carbon::parse($invoice->created_at)->diffInDays(now()) < 30 &&
                        auth()->user()->hasPermissionTo('Editar Facturas'))
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    @endif --}}

                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $det)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th editable scope="row" class="px-6 ">
                            {{ $det->product->code }}
                        </th>
                        <td scope="row" class="px-6 ">
                            {{ $det->product->name }}
                        </td>
                        <td class="px-6 py-1">
                            {{ formatNumber($det->cant) }}
                        </td>
                        <td class="px-6 py-1">
                            {{ $det->unit->name }}
                        </td>
                        <td class="px-6 py-1">
                            ${{ formatNumber($det->price) }}
                        </td>
                        <td class="px-6 py-1">
                            ${{ formatNumber($det->taxTotal) }}
                        </td>
                        <td class="px-6 py-1">
                            ${{ formatNumber($det->total) }}
                        </td>
                       {{--  @if (\Carbon\Carbon::parse($invoice->created_at)->diffInDays(now()) < 30 &&
                            auth()->user()->hasPermissionTo('Editar Facturas'))
                            <td class="px-6 py-1 text-right">
                                @livewire('invoices.edit-detail', ['detail' => $det], key(uniqid()))
                            </td>
                        @endif --}}
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
