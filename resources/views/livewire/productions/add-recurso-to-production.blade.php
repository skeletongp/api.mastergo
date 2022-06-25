<div>
    <x-modal :maxWidth="'max-w-3xl'" title="Añadir Recursos" >
        <x-slot name="button">
            <x-tooltip id="{{ $production['id'] }}2">Añadir recursos</x-tooltip>
            <span data-tooltip-target="{{ $production['id'] }}2"
                class="fas fa-angle-double-up text-xl"></span>
        </x-slot>
        <div>
            <form action="" class="py-8 space-y-4" wire:submit.prevent="addSelected">
                <div class="flex space-x-4">
                    <div class="w-full">
                        <x-base-select label="Recurso" wire:model="recurso_id" id="recurso_id">
                            <option value=""></option>
                            @foreach ($recursos as $id => $item)
                                <option value="{{ $id }}">{{ $item }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="recurso_id">Requerido</x-input-error>
                    </div>
                    @if ($brands)
                        <div class="w-full">
                            <x-base-select label="Recurso" wire:model="brand_id" id="brand_id">
                                <option value=""></option>
                                @foreach ($brands as $id => $brd)
                                    <option value="{{ $brd->id }}">{{ $brd->name }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="brand_id">Requerido</x-input-error>
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
                                <span>{{ $item['recurso'] }}</span>
                                <span>{{ $item['brand'] }}</span>
                                <h1 class="text-right">{{ $item['cant'] }}</h1>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex w-full my-4 justify-between ">
                    <div>
                        <x-toggle label="Restar stock" id="restar" wire:model="restar" value="true"></x-toggle>
                    </div>
                    <x-button role="button" class="bg-cyan-700" wire:click="storeSelected">Guardar</x-button>
                </div>
            @endif
        </div>
    </x-modal>
</div>
