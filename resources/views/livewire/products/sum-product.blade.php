<div class="max-w-4xl shadow-xl p-4">
    <form action="" wire:submit.prevent="addProduct">
        <div class="flex space-x-4 items-end pt-8 relative">
            <div class="w-3/6">
                <x-select class="selectProduct" wire:model.defer="form.product_id">
                    <option value=""></option>
                    @foreach ($products as $id => $product)
                        <option value="{{ $id }}">{{ $product }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="form.product_id"></x-input-error>
            </div>
            <div class=" w-1/6">
                <x-input type="number" label="Cantidad" id="form.cant" wire:model.defer="form.cant"></x-input>
                <x-input-error for="form.cant"></x-input-error>
            </div>
            <div class=" w-2/6 px-4" wire:ignore>
                <x-select wire:model.defer="form.unit" class="selectMedida">
                    <option value="">Medida</option>
                    @foreach ($units as $id => $unit)
                        <option value="{{ $id }}">{{ $unit }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="form.unit"></x-input-error>
            </div>
            <div class="absolute top-1 right-1">
                <x-button>
                    <span class="fas fa-plus"></span>
                </x-button>
            </div>
        </div>
    </form>
    <div class="mt-4">
        @if (count($productAdded))
            <div class="px-4 " x-data="{ open: false }" x-cloak>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Producto
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cantidad
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Medida
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <span class="sr-only">
                                        Acciones
                                    </span>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="text-base">
                            @foreach ($productAdded as $added)
                                <tr
                                    class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                    <th scope="row"
                                        class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                        {{ $added['product_name'] }}
                                    </th>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ formatNumber($added['cant']) }}
                                    </td>
                                    <td class="px-6 py-2  cursor-pointer">
                                        {{ $units[$added['unit']] }}
                                    </td>

                                    <td class="px-6 py-2">
                                        <div class="flex space-x-4 w-max mx-auto cursor-pointer ">
                                            <span class="far fa-trash-alt"
                                                wire:click="remove({{ $added['id'] }})"></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
                <div class="flex items-center justify-end mt-4">
                    <div class="mr-6">
                        <x-toggle label="Registrar gasto" id="setCost" wire:model.defer="setCost"></x-toggle>
                    </div>
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled' wire:click.prevent="sumCant">Guardar</x-button>

                </div>
            </div>
        @endif
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('.selectProduct').select2({
                    placeholder: "Seleccione un producto",
                    allowClear: true
                });
                $('.selectProduct').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('form.product_id', data);
                    @this.emit('getUnits');
                });
                $('.selectMedida').select2({
                    placeholder: "Indique la medida",
                    allowClear: true
                });
                $('.selectMedida').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('form.unit', data);
                });
            });
            Livewire.hook('element.updated', function() {
                $(document).ready(function() {
                    $('.selectProduct').select2({
                        placeholder: "Seleccione un producto",
                        allowClear: true
                    });
                    $('.selectProduct').on('change', function(e) {
                        var data = $(this).select2("val");
                        @this.set('form.product_id', data);
                        @this.emit('getUnits');
                    });
                    $('.selectMedida').select2({
                        placeholder: "Indique la medida",
                        allowClear: true
                    });
                    $('.selectMedida').on('change', function(e) {
                        var data = $(this).select2("val");
                        @this.set('form.unit', data);
                    });
                });
            });
        </script>
    @endpush
</div>
