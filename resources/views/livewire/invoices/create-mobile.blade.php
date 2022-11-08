<div x-data="{ itemShowing: 'products' }" x-cloak>
    <div class="px-4">
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-400"></div>
            <span @click="itemShowing='products'" class="flex-shrink mx-4 select-none text-gray-400">Productos</span>
            <div class="flex-grow border-t border-gray-400"></div>
        </div>
        <div class="w-full space-y-2" x-transition x-show="itemShowing=='products'">
            <div class="flex space-x-2 items-end">
                <div class="w-[3rem]">
                    <x-base-input placeholder="Cód." class=" border-none" type="number" wire:model.lazy="product_code" 
                        id="codeInput" label="Cód." >
                    </x-base-input>
                </div>
                <div class="w-40 max-w-[10rem] px-1">
                    <label class=" font-semibold" for="pr_name_mobile">Nombre</label>
                    <x-datalist  value="{{ $product_name }}" class="border-none h-full" inputId="pr_name_mobile"
                        model="product_name" type="search" placeholder="Producto" 
                        tabindex="-1"
                        listName="pr_code_name_mobile"
                      >
                        @foreach ($products as $in => $prod)
                            <option class="bg-gray-200 " value="{{ $in }} {{ $prod }}" 
                            data-value="{{ $in }}">
                            </option>
                        @endforeach
                    </x-datalist>
                </div>
                <div class="w-16">
                    <x-base-input id="cant" class="uppercase border-none text-center  " type="number"
                        placeholder="Cant." wire:keydown.enter="tryAddItems" wire:model.lazy="cant" label="Cant." >
                       
                    </x-base-input>
                </div>
                <div class="w-[3.5rem]">
                    <x-base-select id="unit_id" 
                     class="uppercase border-none text-center  " wire:model="unit_id"
                        label="Unidad">
                        @if ($product)
                            @foreach ($product['units'] as $unit)
                                <option  value="{{ $unit['pivot']['id'] }}">
                                    {{ $unit['symbol'] }}
                                </option>
                            @endforeach
                        @endif
                    </x-base-select>

                </div>
                <div class="w-24">
                    <x-base-input class=" border-none text-center " type="number"
                    tabindex="2"
                        status="{{ auth()->user()->hasPermissionTo('Asignar Precios')? '': 'disabled' }}"
                        placeholder="Precio" wire:model.lazy="price" id="pr_price" wire:keydown.enter="tryAddItems"
                        label="Precio">
                    </x-base-input>
                </div>
                <div class="w-10 pt-2">
                    <x-button class="bg-transparent p-2 text-gray-800 " wire:click="tryAddItems">
                        <span class="fas fa-plus text-lg"></span>
                    </x-button>
                </div>
            </div>
            <div class="flex flex-col space-y-0">
                @foreach ($details as $index => $det)
                    <div class="flex space-x-2 my-0 py-0 items-end">
                        <div class="w-[3rem]">
                            <x-base-input placeholder="Cód." class=" border-none" type="number"
                                id="codeInput{{ $index }}" label="" disabled
                                value="{{ $det['product_code'] }}">
                            </x-base-input>
                        </div>
                        <div class="w-40 max-w-[10rem] px-1">
                            <x-base-input placeholder="Producto" class=" border-none" type="text"
                                id="nameInput{{ $index }}" label="" disabled
                                value="{{ $det['product_name'] }}">
                            </x-base-input>
                        </div>
                        <div class="w-16">
                            <x-base-input placeholder="Cantidad" class=" border-none text-right" type="number"
                                id="cantInput{{ $index }}" label="" disabled
                                value="{{ formatNumber($det['cant']) }}">
                            </x-base-input>
                        </div>
                        <div class="w-[3.5rem]">
                            <x-base-input placeholder="Unidad" class=" border-none" type="text"
                                id="unitInput{{ $index }}" label="" disabled
                                value="{{ $det['unit_name'] }}">
                            </x-base-input>

                        </div>
                        <div class="w-24">
                            <x-base-input placeholder="Precio" class=" border-none text-right" type="text"
                                id="priceInput{{ $index }}" label="" disabled
                                value="${{ formatNumber($det['price']) }}">
                            </x-base-input>
                        </div>
                        <div class=" flex items-center space-x-4 h-full p-2 bg-transparent">
                            <span wire:click="editItem({{ $det['id'] }})"
                                class="  fas fa-pen cursor-pointer text-green-600"></span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-400"></div>
            <span @click="itemShowing='clients'" class="flex-shrink mx-4 select-none text-gray-400">Cliente</span>
            <div class="flex-grow border-t border-gray-400"></div>
        </div>

        <div class="w-full space-y-2" x-transition x-show="itemShowing=='clients'">
            <div class="flex space-x-4 items-end">
                <div class="w-full">
                    <x-base-input class="text-sm uppercase" inputClass="py-1" wire:model.lazy="client_code"
                        id="client_code" label="Cód. Cliente" type="number"></x-base-input>
                    <div class="absolute top-0 h-full right-0 ">

                    </div>
                </div>
                <div class="w-full">
                    <label for="" class="font-bold">Buscar por nombre</label>
                    <x-datalist class="py-1" listName="clientListInvoice" inputId="clientInvoiceID"
                        wire:model.lazy="clientNameCode" wire:keydown.enter.prevent="$emit('focusCodde')">
                        @foreach ($clients as $index => $clte)
                            <option  value="{{ $index . ' - ' . $clte }}"></option>
                        @endforeach
                    </x-datalist>
                </div>
                <div class="w-full">
                    <x-base-input wire:keydown.enter.prevent='rncEnter' wire:model.defer="name"
                        placeholder="Cliente Genérico" class="py-1" label="Nombre/RNC" id="clt.inv.name">
                    </x-base-input>
                </div>
            </div>
            <div class="flex space-x-4 items-end">
                <div class="w-full">
                    <x-base-input class="text-base uppercase" inputClass="py-1" disabled
                        wire:model.defer="client.phone" id="clt.phone" label="Nº. Teléfono" type="tel">
                    </x-base-input>
                </div>
                <div class="w-full">
                    <x-base-input class="text-base uppercase" inputClass="py-1" disabled
                        wire:model.defer="client.rnc" id="clt.rnc" label="RNC/CÉD."></x-base-input>
                </div>
                <div class="w-full">
                    <div class="">
                        <x-base-input class="text-base uppercase" inputClass="py-1" disabled
                            wire:model.defer="client.balance" id="clt.balance" label="Deuda"></x-base-input>
                    </div>
                </div>
            </div>




        </div>
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-400"></div>
            <span @click="itemShowing='details'" class="flex-shrink mx-4 select-none text-gray-400">Detalle</span>
            <div class="flex-grow border-t border-gray-400"></div>
        </div>
        <div class="w-full space-y-2" x-transition x-show="itemShowing=='details'">
            <div class="flex space-x-4 items-end">
                <div class="w-full">
                    <x-base-input id="number" wire:model="number" disabled class="text-right py-1"
                        label="Pedido Nº.">
                    </x-base-input>
                </div>
                <div class="w-full">
                    <x-base-select status="{{ auth()->user()->hasPermissionTo('Fiar Facturas')? '': 'disabled' }}"
                        id="condition" wire:model="condition" class="text-sm uppercase py-1" label="Condición">
                        <option value="De Contado">DE CONTADO</option>
                        <option value="Contra Entrega">CONTRA ENTREGA</option>
                        <option value="1 A 15 DÍAS">1 A 15 DÍAS</option>
                        <option value="16 A 30 DÍAS">16 A 30 DÍAS</option>
                        <option value="31 A 45 DÍAS">31 A 45 DÍAS</option>
                    </x-base-select>
                </div>
                <div class="w-full">
                    <x-base-input id="vence" type="date" wire:model="vence" disabled class="text-right py-1"
                        label="Vencimiento">
                    </x-base-input>
                </div>
            </div>
            <div class="flex space-x-4 items-end">
                <div class="w-full">
                    <x-base-select id="type" wire:model="type" class="text-sm uppercase pt-1 pb-0"
                        label="Tipo de NCF">
                        @foreach (array_slice(App\Models\Invoice::TYPES, 0, 5) as $ind => $type)
                            <option disabled value="{{ $type }}">{{ $ind }}</option>
                        @endforeach
                    </x-base-select>
                    @if (!$compAvail)
                        <span class="text-red-400">Tipo de comprobante no disponible</span>
                    @endif
                </div>
                <div class="w-full">
                    <x-base-input id="ncf" type="text" wire:model="ncf" disabled
                        class="text-left text-base uppercase py-1" label="NCF">
                    </x-base-input>
                </div>
            </div>
        </div>
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-gray-400"></div>
            <span class="flex-shrink mx-4 select-none text-gray-400">Finalizar</span>
            <div class="flex-grow border-t border-gray-400"></div>
        </div>
        <div class="w-full space-y-2">
            @if (count($details))
            <div class="flex space-x-2 my-0 py-0 items-end">
                <div class="w-[4.5rem]">
                    <x-base-input placeholder="" class=" border-none" type="text"
                        id="codeInput{{ $index }}" label="Artículos" disabled
                        value="{{ count($details) }}">
                    </x-base-input>
                </div>
                <div class="w-full text-xl">
                    <x-base-input placeholder="" class=" border-none !text-2xl font-bold" type="text"
                        id="codeInput{{ $index }}" label="Descuento" disabled
                        value="${{ formatNumber(array_sum(array_column($details, 'discount'))) }}">
                    </x-base-input>
                </div>
                <div class="w-full text-xl">
                    <x-base-input placeholder="" class=" border-none !text-2xl font-bold" type="text"
                        id="codeInput{{ $index }}" label="Total" disabled
                        value="${{ formatNumber(array_sum(array_column($details, 'total'))) }}">
                    </x-base-input>
                </div>
                <div class="w-full h-full flex space-x-2 items-center font-bold uppercase  justify-center text-center" >
                    <x-button class="flex space-x-2  items-center font-bold uppercase rounded-xl py-2 w-full bg-cyan-600 disabled:bg-gray-200 disabled:text-gray-400" wire:click="trySendInvoice" id="btntrySendInvoice" wire:loading.attr='disabled'>
                        <span class="fas fa-save text-xl"></span>
                        <span class="">Facturar</span>
                    </x-button>
                </div>
               
            </div>
            @endif
        </div>
    </div>
</div>
