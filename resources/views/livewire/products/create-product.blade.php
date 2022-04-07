<div class="p-2 px-4 pb-6 shadow-lg ">
    <h1 class="py-4 font-bold text-center uppercase text-xl">Registrar nuevo producto</h1>
    <div class="flex space-x-6">
        <form action="" wire:submit.prevent="createProduct" id="form1" class="max-w-md w-full">
            <div class="flex flex-col space-y-4 items center max-w-4xl">
                <div>
                    <x-input required maxLength="30" label="Nombre del producto" id="form.product.name"
                        wire:model.defer="form.name"></x-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div>
                    <div wire:ignore class="space-y-2">
                        <label >Descripción del producto</label>
                        <textarea class="hidden"  id="editor" wire:model.defer="form.description"></textarea>
                    </div>
                    <x-input-error for="form.description"></x-input-error>
                </div>
                <h1 class="uppercase text-xl font-bold">Impuestos</h1>
                <div class="w-full grid grid-cols-4 gap-6 ">
                    @foreach ($taxes as $id => $tax)
                        <div>
                            <x-toggle name="taxSelected" wire:model.defer="taxSelected" value="{{$id}}" label="{{ $tax }}" id="toggle{{ $id }}"></x-toggle>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="w-full grid grid-cols-1 gap-6 ">
                    <h1 class="uppercase text-xl font-bold">Sucursales</h1>
                    @foreach ($places as $id => $place)
                        <div>
                            <x-toggle name="placeSelected" wire:model.defer="placeSelected" value="{{$id}}" label="{{ $place }}" id="togglePlace{{ $id }}"></x-toggle>
                        </div>
                    @endforeach
                    <x-input-error for="placeSelected">Seleccione al menos una sucursal</x-input-error>
                </div>
                <hr>
                <div class="flex space-x-4">
                    <div class="w-full">
                        <label for="photo" class="flex items-center space-x-4 pb-4 cursor-pointer">
                            <span class="fas fa-image text-xl"></span>
                            <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen</span>
                            @if ($photo)
                                <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                    {{ Universal::formatNumber($photo->getSize() / 1024) }} KB</span>
                            @endif
                            <input wire:model="photo" type="file" class="hidden" name="photo" id="photo"
                                accept="image/png, image/gif, image/jpeg">
                        </label>
                        <hr>
                        <x-input-error for="photo" />
                    </div>
                    @if ($photo)
                        <div class="w-12 h-12 rounded-full bg-cover bg-center"
                            style="background-image: url({{ $photo->temporaryUrl() }})">
                        </div>
                    @endif
                    <div class="">
                        <x-button class="space-x-2 z-50 text-sm flex items-center" wire:target="photo" wire:loading>
                            <div class="animate-spin">
                                <span class="fa fa-spinner ">
                                </span>
                            </div>
                            <h1>Procesando</h1>
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
        <form action="" id="form2" wire:submit.prevent="addUnit" class="mt-4 max-w-sm mx-auto">
            <div class="flex space-x-4 items-end">
                <div class="w-full">
                    <label for="unit_id">Precio por Medida</label>
                    <x-select name="unit_id" wire:model.defer="unit_id" id="unit_id">
                        <option value=""></option>
                        @foreach ($units as $id => $unit)
                            <option value="{{ $id }}">{{ $unit }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="unit_id"></x-input-error>
                </div>

                <div class="pb-2">
                    <x-button class=" uppercase disabled:bg-gray-200 text-xs" wire:loading.attr='disabled' form="form2">
                        <div class="animate-spin mr-2" wire:loading wire:target="addUnit">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        <span class="fas fa-plus"></span>
                    </x-button>
                </div>
            </div>
            <div class="flex space-x-4 items-start">
                <div class="pt-4">
                    <x-input label="Costo" type="number" step="any" min="0"  id="unit_cost" name="unit_cost"
                        wire:model.lazy="unit_cost"></x-input>
                    <x-input-error for="unit_cost"></x-input-error>
                </div>
                <div class="pt-4">
                    <x-input label="Precio" type="number" step="any" min="0"  id="unit_price" name="unit_price"
                        wire:model.lazy="unit_price"></x-input>
                    <x-input-error for="unit_price"></x-input-error>
                </div>
                <div class="pt-4">
                    <x-input label="Margin (%)" type="number" step="any" min="0"  id="unit_margin"
                        name="unit_margin" wire:model.lazy="unit_margin"></x-input>
                    <x-input-error for="unit_price"></x-input-error>
                </div>
            </div>
            <x-input-error for="unitSelected">Añada por lo menos un precio por medida</x-input-error>
            <div class="mt-4">
                @if (count($unitSelected))
                    <div class="" x-data="{ open: false }" x-cloak>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Unidad
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Precio
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Costo
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Margen
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            <span class="sr-only">
                                                Acciones
                                            </span>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="text-base">
                                    @foreach ($unitSelected as $uniSel)
                                        <tr
                                            class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                            <th scope="row"
                                                class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                                {{ $units[$uniSel['unit_id']] }}
                                            </th>
                                            <td class="px-6 py-2  cursor-pointer">
                                                {{ Universal::formatNumber($uniSel['price']) }}
                                            </td>
                                            <td class="px-6 py-2  cursor-pointer">
                                                {{ Universal::formatNumber($uniSel['cost']) }}
                                            </td>
                                            <td class="px-6 py-2  cursor-pointer">
                                                {{ Universal::formatNumber($uniSel['margin'] * 100) }}%
                                            </td>
                                            <td class="px-6 py-2">
                                                <div class="flex space-x-4 w-max mx-auto cursor-pointer ">
                                                    <span class="far fa-trash-alt"
                                                        wire:click="remove({{ $uniSel['id'] }})"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>

                    </div>
                @endif
            </div>
        </form>


    </div>

    <div class="flex justify-end mt-8">
        <div class="">
            <x-button form="form1" class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                wire:loading.attr='disabled'>
                <div class="animate-spin mr-2" wire:loading wire:target="createProduct">
                    <span class="fa fa-spinner ">
                    </span>
                </div>
                <span>Guardar</span>
            </x-button>
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
                        editor.model.document.on('change:data', () => {
                            @this.set('form.description', editor.getData());
                        })
                    })
                    .catch(error => {
                        console.error(error);
                    });

            });
        </script>
    @endpush
</div>
