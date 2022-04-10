<div class="flex space-x-4 ">
    <div class="w-1/2 pt-8">
        <h1 class="text-center uppercase font-bold text-xl">Facturar productos</h1>
        <form action="" wire:submit.prevent="addItems">
            <div class="flex space-x-4 py-4 items-end">
                <div class="w-full">
                    <x-select wire:model="form.product_id" class="selectProduct" id="selectProduct">
                        <option value=""></option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                @if ($producto)
                    <div class="max-w-[12rem] w-full">
                        <x-select wire:model.defer="form.unit_id" wire:change="selUnit" class=""
                            id="selectProduct">
                            <option value=""></option>
                            @foreach ($producto->units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                @endif
                <div class="max-w-[4rem]">
                    <x-tooltip id="ttCant">Máx. {{ $maxCant }}</x-tooltip>
                    <x-input label="Cant" max="{{ $maxCant }}" idTT="ttCant" id="invCant" type="number"
                        wire:model.defer="form.cant"></x-input>
                </div>
                <div class="max-w-[6rem]">
                    <x-input label="Precio" id="invPrice" wire:model.defer="form.price"></x-input>
                </div>
                <div class="h-full flex items-end">
                    <x-button>
                        <span class="far fa-plus"></span>
                    </x-button>
                </div>
            </div>
        </form>
        <div class="py-8">
            @if (count($details))
                <table class="w-full text-base ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                               Producto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cant.
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Medida
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Precio
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $det)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class=" text-left px-6 py-4 font-bold text-gray-900 ">
                                    {{ $det['product_name'] }}
                                </td>
                                <td class="px-6 py-4  font-bold text-right">
                                    {{ Universal::formatNumber($det['cant']) }}
                                </td>
                                <td class="px-6 py-4  font-bold text-right">
                                    {{ $det['unit_name'] }}
                                </td>
                                <td class="px-6 py-4  font-bold text-right">
                                    {{ Universal::formatNumber($det['price']) }}
                                </td>
                                <td class="px-6 py-4  font-bold text-right">
                                    {{ Universal::formatNumber($det['total']) }}
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="w-1/2 flex flex-col items-center">
        <div class="w-48 h-48 rounded-full bg-cover bg-center "
            style="background-image: url({{ auth()->user()->store->logo }})">
        </div>
        <h1 class="uppercase text-3xl font-bold">{{ auth()->user()->store->name }}</h1>
        <div class="p-4 w-full max-w-[32rem]">
            <table class="w-full  ">
                <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6 py-4 font-bold text-xl uppercase text-gray-900 ">
                            Artículos
                        </th>
                        <td class="px-6 py-4 text-3xl font-bold text-right">
                            {{ Universal::formatNumber(count($details)) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6 py-4 font-bold text-xl uppercase text-gray-900 ">
                            Monto
                        </th>
                        <td class="px-6 py-4 text-3xl font-bold text-right">
                            ${{ Universal::formatNumber(array_sum(array_column($details, 'total'))) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6 py-4 font-bold text-xl uppercase text-gray-900 ">
                            ITBIS
                        </th>
                        <td class="px-6 py-4 text-3xl font-bold text-right">
                            ${{ Universal::formatNumber(array_sum(array_column($details, 'total')) * 0.18) }}
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class=" text-left px-6 py-4 font-bold text-xl uppercase text-gray-900 ">
                            TOTAL
                        </th>
                        <td class="px-6 py-4 text-3xl font-bold text-right">
                            ${{ Universal::formatNumber(array_sum(array_column($details, 'total'))* 1.18) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function() {
            $('.selectProduct').select2({
                placeholder: "Seleccione un producto",
                allowClear: false
            });
            $('.selectProduct').on('change', function(e) {
                var data = $(this).select2("val");
                @this.set('form.product_id', data);
                Livewire.emit('selProducto', data);
                console.log(data)
            });
            $('#selectProduct').select2('open');
            $('.select2-search__field:first').focus();
            $('.select2-search__field:first').trigger('input');
        });
        Livewire.hook('element.updated', function() {
            $(document).ready(function() {
                $('.selectProduct').select2({
                    placeholder: "Seleccione un producto",
                    allowClear: false
                });
                $('.selectProduct').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('form.product_id', data);
                    Livewire.emit('selProducto', data);
                    console.log(data)
                });
                $('#selectProduct').select2('open');
                $('.select2-search__field:first').focus();
                $('.select2-search__field:first').trigger('input');
            });
        });
    </script>
@endpush
