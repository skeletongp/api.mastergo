<div class="px-4 mt-8" x-data="{ open: false }" x-cloak>
    <h1 class="font-semibold text-left uppercase mb-6">Unidades registradas</h1>
    <div class=" overflow-hidden shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Impuesto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tasa
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        <span class="sr-only">
                            Acciones
                        </span>
                    </th>

                </tr>
            </thead>
            <tbody class="text-base">
                @foreach ($taxes as $tax)
                    <tr
                        class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                        <th scope="row" class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                            {{ $tax->name }}
                        </th>
                        <td class="px-6 py-2  cursor-pointer">
                            {{ Universal::formatNumber($tax->rate*100) }}%
                        </td>
                        <td class="px-6 py-2">
                            <div class="flex space-x-4 w-max mx-auto ">
                                @if ($tax->name !== 'ITBIS')
                                    <livewire:general.delete-model title="Impuesto" permission="Borrar Impuestos"
                                        :model="$tax" event="reloadTaxes" :wire:key="uniqid().'tax'" />
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>


    </div>

</div>
