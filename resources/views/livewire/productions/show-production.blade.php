<div>
    <x-modal title="{{ $production['proceso']['name'] }}"  maxWidth="max-w-5xl">

        <x-slot name="button">
            <span class="fas fa-eye"></span>
        </x-slot>
        <div>
            <div class="grid grid-cols-2 gap-6">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <h1 class="text-center font-bold uppercase text-xl my-4">Recursos invertidos</h1>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Recurso
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Atributo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cantidad
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($production['brands'] as $brand)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $brand['recurso']['name'] }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $brand['name'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatNumber($brand['cant']) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex space-x-6">
                                            <span class="far fa-pen text-blue-300"></span>
                                            <span class="far fa-trash text-red-400"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <h1 class="text-center font-bold uppercase text-xl my-4">Productos obtenidos</h1>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Producto
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Atributo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cantidad
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($production['products'] as $product)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $product['productible']['name'] }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $product['unitable']['name'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatNumber($product['cant']) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex space-x-6">
                                            <span class="far fa-pen text-blue-300"></span>
                                            <span class="far fa-trash text-red-400  cursor-pointer"
                                                wire:click="confirm('¿Eliminar resultado?','deleteProduct', {{$product['id']}},'Crear Usuarios')"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal>
</div>
