<div class="w-full flex space-x-4 items-start">
    
    <div class="w-full h-full shadow-xl p-4 ">
        <form wire:submit.prevent="updateProduct" class="space-y-4 max-w-xl w-full ">
            <h1 class="text-center pb-4 uppercase font-bold text-xl">Información del producto</h1>
            <div class="w-full">
                <x-input label="Nombre del producto" wire:model.defer="product.name" id="product.name"></x-input>
            </div>
            <div class="w-full" wire:ignore>
                <textarea wire:model.defer="product.description" id="editor"></textarea>
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
                <div class="w-[3rem] h-[3rem] rounded-full bg-center bg-cover" style="background-image: url({{$photo_path?$photo->temporaryUrl() : $product->photo}})">

                </div>
            </div>
            <div class="flex justify-end"  >
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
            <form action="" wire:submit.prevent="updatePrice ">
                <h1 class="text-center pb-4 uppercase font-bold text-xl">Detalles de precio</h1>
                <div class="space-y-4">
                    @foreach ($units as $unit)
                        <div class="flex space-x-4 items-end pt-4">
                            <x-input class="font-bold uppercase" readonly value="{{$unit->name}}" label="Medida" id="medida{{$unit->id}}"></x-input>
                            <div class="max-w-xs">
                                <x-input label="Costo" type="number" id="cost{{ $unit->id }}"
                                    x-value="{{ $unit->id }}" wire:model.defer="unit.{{ $unit->symbol }}.cost">
                                </x-input>
                                <x-input-error for="unit.{{ $unit->symbol }}.cost"></x-input-error>
                            </div>
                            <div class="max-w-xs">
                                <x-input label="Precio" type="number" id="price{{ $unit->id }}"
                                    wire:model.defer="unit.{{ $unit->symbol}}.price"></x-input>
                                    <x-input-error for="unit.{{ $unit->symbol}}.price"></x-input-error>
                            </div>
                            <div class="max-w-xs">
                                <x-button>
                                    <span class="fas fa-save"></span>
                                </x-button>
                            </div>
                            <div class="max-w-xs">
                                <x-button type="button" class="bg-gray-200" wire:click.prevent="deleteUnit('{{$unit->symbol}}')">
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
@push('js')
    <script>
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: ["heading", "|", "bold", "italic", "link", "bulletedList", "numberedList", "|",
                        "blockQuote", "undo", "redo"
                    ]
                })
                .then(editor => {
                    $('#editor').removeClass("hidden");
                    editor.config.removePlugins = 'uploadImage'
                    console.log(editor.config._config.toolbar)
                    editor.model.document.on('change:data', () => {
                        @this.set('product.description', editor.getData());
                    })
                })
                .catch(error => {
                    console.error(error);
                });

            });
    </script>
@endpush
