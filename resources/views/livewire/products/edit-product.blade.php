<div class="w-full max-w-6xl flex space-x-4 items-start">

    <div class="w-2/5 h-full shadow-xl p-4 ">
        <form wire:submit.prevent="updateProduct" class="space-y-4 max-w-xl w-full ">
            <h1 class="text-center pb-4 uppercase font-bold text-xl">Información del producto</h1>
            <div class="w-full">
                <x-base-input label="Nombre del producto" wire:model.defer="product.name" id="product.name"></x-base-input>
            </div>
            <div class="pt-4 pb-2">
                <div class="space-y-2">
                    <label>Descripción del producto</label>
                    <textarea rows="2"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 resize-none"
                        placeholder="Breve descripción del producto" id="product.description" wire:model.defer="product.description"></textarea>
                </div>
                <x-input-error for="producto.description"></x-input-error>
            </div>
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <label for="photo{{ $product->id }}" class="flex items-center space-x-4 pb-2 cursor-pointer">
                        <span class="fas fa-image text-xl"></span>
                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Foto del producto</span>
                        <input wire:model="photo" type="file" class="hidden" name="photo"
                            id="photo{{ $product->id }}" accept=".png, .gif, .jpeg">
                    </label>
                    <x-input-error for="photo"></x-input-error>
                </div>
                <div class="w-[3rem] h-[3rem] rounded-full bg-center bg-cover"
                    style="background-image: url({{ $photo_path ? $photo->temporaryUrl() : $product->photo }})">

                </div>
            </div>
            <div class="w-full">
                <x-base-select wire:model.defer="product.type" label="Tipo de producto">
                    <option value=""></option>
                    <option  >Producto</option>
                    <option >Servicio</option>
                </x-base-select>
            <x-input-error for="product.type"></x-input-error>
            </div>
            <div class="flex justify-end pb-2 pt-1">
                <x-button wire:loading.attr="disabled">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>
    <div class="w-full h-full shadow-xl p-4">
        @can('Cambiar Precios')
            <livewire:products.product-new-price :product="$product" />
        @endcan
        <div class="py-4">
            <hr>
        </div>
        {{-- Product Price --}}
        @can('Cambiar Precios')
            <form action="" wire:submit.prevent="confirm('¿Desea actualizar el precio?', 'updatePrice', 'Cambiar Precio')"  >
                <h1 class="text-center pb-4 uppercase font-bold text-xl">Detalles de precio</h1>
                <div class="space-y-4">
                    @foreach ($units as $unt)
                        <div class="flex space-x-4 items-start pt-4">
                            <div>
                                <x-base-input class="font-bold uppercase" readonly value="{{ $unt->name }}" label="Medida"
                                    id="medida{{ $unt->id }}"></x-base-input>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Costo" type="number" id="cost{{ $unt->id }}"
                                    x-value="{{ $unt->id }}" wire:model.defer="unit.{{ $unt->id }}.cost">
                                </x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.cost">Requerido</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Detalle" type="number" id="price{{ $unt->id }}.menor"
                                    wire:model.defer="unit.{{ $unt->id }}.price_menor"></x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.price_menor">Min. 1</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Mayor" type="number" id="price{{ $unt->id }}.mayor"
                                    wire:model.defer="unit.{{ $unt->id }}.price_mayor"></x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.price_mayor">Min. 1</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Especial" type="number" id="price{{ $unt->id }}.special"
                                    wire:model.defer="unit.{{ $unt->id }}.price_special"></x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.price_special">Requerido</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Descuento" type="number" id="price{{ $unt->id }}.discount"
                                    wire:model.defer="unit.{{ $unt->id }}.discount"></x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.discount">Requerido</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-base-input label="Mínimo" type="number" id="price{{ $unt->id }}.min"
                                    wire:model.defer="unit.{{ $unt->id }}.min"></x-base-input>
                                <x-input-error for="unit.{{ $unt->id }}.min">Min. 1</x-input-error>
                            </div>
                            <div class="max-w-xs w-1/4">
                                <x-button>
                                    <span class="fas fa-save"></span>
                                </x-button>
                            </div>
                            <div class="max-w-xs">
                                <x-button type="button" class="bg-gray-200"
                                    wire:click.prevent="deleteUnit('{{ $unt->id }}')">
                                    <span class="fas fa-trash-alt text-red-600"></span>
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        @endcan

        <div class="py-4">
            <hr>
        </div>


        {{-- Product Tax --}}
        <form action="" wire:submit.prevent="updateTax ">
            <h1 class="text-center pb-4 uppercase font-bold text-xl">Impuestos aplicables</h1>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($taxes as $id => $tax)
                    <div>
                        <x-toggle id="tax{{ $id }}" checked="{{ in_array($tax, $taxSelected) }}"
                            label="{{ $tax }}" value="{{ $id }}" wire:model.defer="taxSelected">
                        </x-toggle>
                    </div>
                @endforeach
            </div>
            <div class="py-4 flex justify-end">
                <x-button wire:loading.attr="disabled">
                    Actualizar
                </x-button>
            </div>
        </form>
    </div>
</div>
