<div class=" p-8" x-data="{ open: true }" x-cloak>
    <div class="max-w-md mx-auto p-4">
        @can('Crear Scopes')
            <livewire:settings.scopes.create-scope />
        @endcan
    </div>
    <br>
    <hr>
    <h1 class="text-center uppercase font-bold text-xl mt-4 mb-6 flex justify-between items-center cursor-pointer"
        @click="open = !open">
        <span>Gestionar Scopes</span>
        <x-tooltip id="manageScopes">
            Ver/Cambiar Scopes
        </x-tooltip>
        <span data-tooltip-target="manageScopes" class="far fa-pen-square"
            x-bind:class=" open ? ' text-green-600' : 'text-yellow-600'"></span>
    </h1>
    <form action="" wire:submit.prevent="changeScopes" x-show="open" x-transition>
        <div class="grid grid-cols-4 gap-6">
            @foreach ($scopes->sort() as $id => $scope)
                <div class="flex space-x-2 items-center mb-4 p-2 shadow-lg">
                    @can('Editar Scopes')
                        <input type="checkbox" name="scopes" id="scope{{ $id }}" wire:loading.attr="disabled"
                            wire:target="scopesSelected" wire:model="scopesSelected.{{ $id }}"
                            value="{{ $scope }}"
                            class="w-6 h-6 text-blue-600 rounded-full bg-gray-100  border-gray-300 focus:ring-blue-500  cursor-pointer disabled:cursor-default"
                            x-bind:checked="selectAll">
                    @endcan
                    <label for="scope{{ $id }}"
                        class="ml-3 text-sm font-medium text-gray-900 text-center dark:text-gray-300 cursor-pointer">
                        {{ $scope }}
                    </label>
                    @can('Borrar Scopes')
                        <span class="fas fa-times text-red-500 cursor-pointer"
                            wire:click=" confirm('¿Eliminar Scope?', 'deleteScope', ''{{ $scope }}')"></span>
                    @endcan
                </div>
            @endforeach

        </div>

        @can('Editar Scopes')
            <div class="flex  justify-between pt-8">
                <div class="">
                    <x-toggle checked="{{ $selectAll }}"
                        label="{{ $selectAll ? 'Deseleccionar todos' : 'Seleccionar todos' }}" id="selectAll"
                        wire:click="$set('selectAll',{{ !$selectAll }})"></x-toggle>
                </div>
                <x-button
                    class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-800 text-xs"
                    wire:loading.attr='disabled'>
                    <div class="animate-spin mr-2" wire:loading>
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    <span>Actualizar</span>
                </x-button>
            </div>
        @endcan
    </form>
</div>
