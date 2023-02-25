<div>
    <x-modal title="Detalles de la compra" :listenOpen="true" maxWidth="max-w-[45rem]">
        <x-slot name="button">
           <span> {{ $provision_code }}</span>

        </x-slot>
        <div class="max-w-[45rem] w-full  overflow-auto max-h-[50vh] py-2">
            {{-- tailwind striped table --}}
           @if (count($provisions))
           <table class=" table-fixed w-full mx-auto  overflow-y-auto  max-h-[40vh]">
            <thead class=" sticky top-0">
                <tr class="bg-gray-100 ">
                    <th class="px-3 sticky-0 top-0 py-2 w-3/12">Producto</th>
                    <th class="px-3 sticky-0 top-0 py-2 w-2/12">Cantidad</th>
                    <th class="px-3 sticky-0 top-0 py-2 w-2/2">Precio</th>
                    <th class="px-3 sticky-0 top-0 py-2 w-2/12">Total</th>
                    <th class="px-3 sticky-0 top-0 py-2 w-3/12">Proveedor</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($provisions as $provision)

                    <tr class="even:bg-gray-100">
                        <td class="border px-3 py-2 text-left text-sm overflow-hidden overflow-ellipsis whitespace-nowrap">{{ $provision->provisionable->name }}</td>
                        <td class="border px-3 py-2 text-right text-sm">{{ formatNumber($provision->cant) }}</td>
                        <td class="border px-3 py-2 text-right text-sm">${{ formatNumber($provision->cost) }}</td>
                        <td class="border px-3 py-2 text-right text-sm font-bold">${{ formatNumber($provision->total) }}</td>
                        <td class="border px-3 py-2">{{ $provision->provider->fullname }}</td>
                    </tr>
                @endforeach

                    <tr class="bg-gray-100">
                        <td colspan="3" class="border px-3 py-2 text-right text-sm font-bold">Total</td>
                        <td class="border px-3 py-2 text-right text-sm font-bold">${{ formatNumber($provisions->sum('total')) }}</td>
                        <td class="border px-3 py-2"></td>
                    </tr>
            </tbody>
        </table>
           @endif



        </div>
    </x-modal>
</div>
