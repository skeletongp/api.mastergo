<div class="px-4 mt-8" x-cloak>
    <h1 class="font-semibold text-left uppercase mb-6">Comprobantes registrados</h1>
    <div class="overflow-hidden shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Serie (NCF)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        <span class="sr-only">
                            Acciones
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($comprobantes as $comprobante)
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                        <th scope="row"
                            class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer"
                            wire:click="$set('comprobantee_name','{{ $comprobante->name }}')">
                            {{ $comprobante->type }}
                        </th>
                        <td class="px-6 py-2  cursor-pointer">
                            {{ $comprobante->prefix . $comprobante->number }}
                        </td>
                        <td class="px-6 py-2  cursor-pointer">
                            {{ $comprobante->status }}
                        </td>
                        <td class="px-6 py-2">
                            <div class="flex space-x-4 w-max mx-auto ">
                                <div class="relative">
                                    <livewire:general.delete-model title="Registro" permission="Borrar Comprobantes"
                                        :model="$comprobante" event="reloadComprobantes"
                                        :wire:key="uniqid().'comprobante'" />
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>


    </div>

</div>
