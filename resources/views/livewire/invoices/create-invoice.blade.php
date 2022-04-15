<div class="w-full">
    <div class="grid grid-cols-12 gap-2 ">
        {{-- Área de productos --}}
        <div class="col-span-5 relative">
            <x-modal id="modalSale" fitVerticalContainer='true'>
                <x-slot name="button">
                    <span class="fas fa-search mr-2"></span>
                    <span>Buscar <small>(AltRight)</small></span>
                </x-slot>
                <x-slot name="title">Buscar por nombre o descripción</x-slot>
                <div class="p-4 max-w-sm">
                    <x-input onfocus="setFocused(true)" onblur="setFocused(false)"
                    label="Buscar producto" type="search" id="prodsearch" wire:model="search"> </x-input>
                    @if ($search && $products->count())
                        <ul class="bg-white rounded-lg border border-gray-200 w-96 py-2 text-gray-900">
                            @foreach ($products as $ind => $pr)
                                @if ($ind < 4)
                                    <li wire:click="setProduct({{ $pr->id }})" onclick="closeModal()"
                                        class=" cursor-pointer px-6 py-2 border-b border-gray-200 w-full rounded-t-lg">
                                        {{ $pr->name }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </x-modal>
            <div class="  grid grid-cols-3 gap-10 p-6 shadow-lg">
                @forelse ($products as $prod)
                    <div class="relative min-h-[9rem] {{optional($producto)->id==$prod->id?'shadow-inner shadow-lg shadow-cyan-300 p-2':''}} mb-4 max-h-[9rem] select-none cursor-pointer"
                        wire:click="setProduct({{ $prod->id }})">
                        <div class="w-full h-full bg-contain bg-no-repeat bg-center"
                            style="background-image: url({{ $prod->photo }})">

                        </div>
                        <h1
                            class=" py-2  overflow-hidden overflow-ellipsis whitespace-nowrap uppercase font-bold mx-auto ">
                            {{ $prod->name }}</h1>
                    </div>
                @empty
                <h1>No se halló ningún resultado</h1>
                @endforelse
            </div>
            @if ($products->count())
                {{$products->links()}}
            @endif
        </div>

        {{-- Área de montos --}}
        <div class="col-span-3 flex flex-col justify-start h-full shadow-lg">
            <x-keyboard></x-keyboard>

            <div class="w-full ">
                <ul class="p-3">
                    @foreach ($errors->toArray() as $ind => $error)
                        <x-input-error for="{{ $ind }}"></x-input-error>
                    @endforeach
                </ul>
                @if ($producto)
                    <h1 class="text-2xl font-bold uppercase p-4 overflow-hidden overflow-ellipsis whitespace-nowrap ">
                        {{ $producto->name }}</h1>
                    <div class=" space-x-2 h-full w-max px-4 text-lg font-bold uppercase">
                        <h1 class="text-left font-bold uppercase mb-4"> MEDIDA <small>máx. {{$maxCant}}</small></h1>
                        <div class=" grid grid-cols-3 gap-2">
                            @foreach ($producto->units as $unit)
                                <div class="auto-cols-max">
                                    <x-radio label="{{ $unit->name }}" id="unit{{ $unit->pivot->id }}" name="unit"
                                        value="{{ $unit->pivot->id }}" wire:model="form.unit_id"></x-radio>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-4 py-8">
                            <x-input label="Precio" onfocus="setFocused(true)" onblur="setFocused(false)" type="number" wire:model.defer="form.price" id="formInvPrice">
                            </x-input>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Area de facturados --}}
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
                                    {{ Universal::formatNumber(count($details)) }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                                    Monto
                                </th>
                                <td class="px-6  text-2xl font-bold text-right">
                                    ${{ Universal::formatNumber(array_sum(array_column($details, 'total'))) }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                                    ITBIS
                                </th>
                                <td class="px-6  text-2xl font-bold text-right">
                                    ${{ Universal::formatNumber(array_sum(array_column($details, 'total')) * 0.18) }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class=" text-left px-6  font-bold text-xl uppercase text-gray-900 ">
                                    TOTAL
                                </th>
                                <td class="px-6  text-3xl font-bold text-right">
                                    ${{ Universal::formatNumber(array_sum(array_column($details, 'total')) * 1.18) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-end ">
                        @if (count($details))
                            <x-button wire:click.prevent="sendInvoice">
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
                                <th scope="col" class="px-6 py-3 text-left">
                                    Precio
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_reverse($details, true) as $det)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-lg">
                                    <td scope="row" class=" text-left px-6  font-bold text-gray-900 ">
                                        <h1 class="overflow-hidden overflow-ellipsis whitespace-nowrap max-w-[9rem]">
                                            {{ $det['product_name'] }}
                                        </h1>
                                    </td>
                                    <td class="px-6   font-bold text-left">
                                        <h1> {{ $det['cant'] }}<span
                                                class="text-xs">{{ $det['unit_name'] }}</span></h1>
                                    </td>
                                    <td class="px-6   font-bold text-right">
                                        ${{ Universal::formatNumber($det['price']) }}
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
    </div>
    @push('js')
        <script>
            let input = document.getElementById('outputScreen');

            function clr() {
                input.value = '';
                @this.set('form.cant', parseFloat(input.value));
            }

            // Del button 
            function del() {
                input.value = input.value.substring(0, input.value.length - 1);
                @this.set('form.cant', parseFloat(input.value));
            }
            // Making button works 
            function display(e, n) {
                e.target.blur()
                if(input.value=='0.000'){
                    clr();
                }
                if (input.value.length > 11) {
                    return;
                }
                if (n == '.' && input.value.includes('.')) {
                    return;
                }
                if (n == '.' && input.value.length < 1) {
                    n = '0.';
                }
                if (input.value == '0' && n !== '.') {
                    input.value = '';
                    input.value += n;
                    return;
                }
                input.value += n;
                @this.set('form.cant', parseFloat(input.value));
            }
            // Enable Keyboard Input
            document.addEventListener("keydown", key, false);
         
            isFocused=false;
            function setFocused(val) {
                isFocused=val;
            }

            function key(e) {
                if (!isFocused) {
                    var keyCode = e.key || e.which;
                    var num = keyCode;
                    // console.log(num)
                    if (!isNaN(parseInt(num)) || num == '.') {
                        display(e, num);
                    } else if (num == 'Backspace') {
                        del();
                    } else if (num == 'Enter') {
                        Livewire.emit('addItems');
                        setTimeout(() => {
                            clr();
                        }, 500);
                    } else if (num == 'Delete') {
                        clr();
                    } else if (num == 'F7' || num == 'AltGraph') {
                        $('#btnmodalSale').click();
                        open = !open;
                        setTimeout(() => {
                            document.getElementById("prodsearch").focus();
                        }, 0);

                    }

                }
            }
            function facturar() {
                document.dispatchEvent(new KeyboardEvent('keydown', {
                    'key': 'Enter'
                }));
            }
            function closeModal() {
                document.dispatchEvent(new KeyboardEvent('keydown', {
                    'key': 'AltGraph'
                }));
            }
        </script>
    @endpush
</div>
