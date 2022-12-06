<form action="" wire:submit.prevent="createProduct" id="form1" class="max-w-5xl mx-auto w-full">
    <div class="grid grid-cols-5 gap-4 items center w-full max-w-6xl">
        <div class="col-span-5 lg:col-span-2 ">
            <div class="flex space-x-2 items-end">
                <div class="w-full">
                    <x-base-input required maxLength="30" label="Nombre del producto" id="form.product.name"
                        wire:model.defer="form.name"></x-base-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div class="">
                    <x-base-input disabled wire:model.defer="form.code" id="code" label="Cod."></x-base-input>
                </div>
            </div>
            <div>
                <div class="space-y-2 pt-2 lg:pt-7">
                    <label class="font-medium">Descripción del producto</label>
                    <textarea rows="2"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 resize-none"
                        placeholder="Breve descripción del producto" id="form.description" wire:model.defer="form.description"></textarea>
                </div>
                <x-input-error for="form.description"></x-input-error>
            </div>
        </div>
        <div class="space-y-4 col-span-5  lg:col-span-3">
            <h1 class="uppercase text-xl font-bold">Impuestos</h1>
            <div class="w-full grid grid-cols-4 gap-6 ">
                @foreach ($taxes as $id => $tax)
                    <div>
                        <x-toggle name="taxSelected" wire:model.defer="taxSelected" value="{{ $id }}"
                            label="{{ $tax }}" id="toggle{{ $id }}"></x-toggle>
                    </div>
                @endforeach
            </div>
            <hr>
            <div class="w-full col-span-5  lg:col-span-1 grid grid-cols-1 gap-6 ">
                <h1 class="uppercase text-xl font-bold">Sucursales</h1>
                @foreach ($places as $id => $place)
                    <div>
                        <x-toggle name="placeSelected" wire:model.defer="placeSelected" value="{{ $id }}"
                            label="{{ $place }}" id="togglePlace{{ $id }}"></x-toggle>
                    </div>
                @endforeach
                <x-input-error for="placeSelected">Seleccione al menos una sucursal</x-input-error>
            </div>
            <hr>
        </div>
        <div class="flex flex-col lg:flex-row space-x-4 col-span-5  lg:col-span-3">
            <div class="flex space-x-4 w-full">
                <div class="w-full">
                    <x-base-select wire:model.defer="form.type" label="Tipo" id="product.type">
                        <option value="Producto">Producto</option>
                        <option value="Servicio">Servicio</option>
                    </x-base-select>
                    <x-input-error for="form.type"></x-input-error>
                </div>
                <div class="w-full">
                    <x-base-select wire:model.defer="form.origin" label="Origen">
                        <option value="Comprado">Comprado</option>
                        <option value="Procesado">Procesado</option>
                    </x-base-select>
                </div>
            </div>
            <div class="h-full flex items-end ">
                <label for="photo" class="flex items-center space-x-4  cursor-pointer ">
                    <span class="fas fa-image text-xl"></span>
                    <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">{{$photo?$photo->getBaseName():'Imagen'}}</span>
                    <input wire:model="photo" type="file" class="hidden" name="photo" id="photo"
                        accept="image/png, image/gif, image/jpeg">
                </label>
                <x-input-error for="photo" />
            </div>
          
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
<div class=" bottom-0 absolute w-full">
    <div class="p-2 pr-0 flex justify-between w-full">
        <x-button wire:click="$set('activeTab','infoproduct')" disabled="{{ $activeTab == 'infoproduct' }}"
            class="uppercase disabled:bg-gray-200 text-xs">
            Anterior
        </x-button>
        <x-button wire:click="$set('activeTab','unitsproduct')" disabled="{{ $activeTab == 'cantproduct' }}"
            class="uppercase disabled:bg-gray-200 text-xs">
            Siguiente
        </x-button>
    </div>
</div>
