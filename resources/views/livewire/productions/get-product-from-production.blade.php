<div>
    <x-modal title="Obtener resultados" >
     <x-slot name="button">
         <x-tooltip id="{{$production['id']}}1">Obtener resultados</x-tooltip>
         <span data-tooltip-target="{{$production['id']}}1"
         class="fas fa-angle-double-down  text-xl"></span>
     </x-slot>
     <div>
        <form action="" class="py-8 space-y-4" wire:submit.prevent="addSelected">
            <div class="flex space-x-4">
                <div class="w-full">
                    <x-base-select label="Resultado" wire:model="product_id" id="product_id">
                        <option value=""></option>
                        @foreach ($products->sortBy('name') as $prd)
                            <option value="{{$prd->id}}|{{get_class($prd)}}">{{ $prd->name }}</option>
                        @endforeach
                    </x-base-select>
                    <x-input-error for="product_id">Requerido</x-input-error>
                </div>
                @if ($units)
                    <div class="w-full">
                        <x-base-select label="Detalle" wire:model="unit_id" id="unit_id">
                            <option value=""></option>
                            @foreach ($units as $unt)
                                <option value="{{$unt->id}}|{{get_class($unt)}}">{{ $unt->name }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="unit_id">Requerido</x-input-error>
                    </div>
                    <div>
                        <x-base-input type="number" label="Cant." wire:model.defer="cant" id="cant" />
                        <x-input-error for="cant">Requerido</x-input-error>
                    </div>
                @endif
            </div>
            <div class="flex justify-end">
                <x-button>Añadir</x-button>
            </div>
        </form>
        @if (count($selected))
            <div class="py-4 mt-4">
                <div
                    class="relative flex items-center space-x-4 w-full px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg bg-blue-100 uppercase">
                    <span class="fas fa-times text-blue-100"></span>
                    <div class=" w-full grid grid-cols-4 ">
                        <span>#</span>
                        <span>Recurso</span>
                        <span>Atributo</span>
                        <h1 class="text-right">CANT </h1>
                    </div>
                </div>
                @foreach ($selected as $id => $item)
                    <div
                        class="relative flex items-center space-x-4 w-full px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                        <span class="fas fa-times text-red-400"
                            wire:click="removeRecurso('{{ $id }}')"></span>
                        <div class=" w-full grid grid-cols-4 ">
                            <span>{{ $id + 1 }}</span>
                            <span>{{ $item['product'] }}</span>
                            <span>{{ $item['unit'] }}</span>
                            <h1 class="text-right">{{ $item['cant'] }}</h1>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex w-full my-4 justify-between ">
                
                <x-button role="button" class="bg-cyan-700" wire:click="storeSelected">Guardar</x-button>
            </div>
        @endif
    </div>
    </x-modal>
 </div>
 