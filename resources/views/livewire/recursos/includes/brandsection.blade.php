<div>
    <form action="" wire:submit.prevent="addBrand">
        <div class="flex space-x-4">
            <div class="w-full">
                <x-base-input label="Atributo" wire:model.defer="marca" id="marca" wire:keydown.enter="addBrand">
                </x-base-input>
                <x-input-error for="marca">Requerido</x-input-error>
            </div>
            <div class="w-1/4">
                <x-base-input type="number" id="cant" label="Cantidad" wire:model.defer="cant"
                    wire:keydown.enter="addBrand">
                </x-base-input>
                <x-input-error for="cant">Requerido</x-input-error>
            </div>
            <div class="w-1/4">
                <x-base-input type="number" id="cost" label="Costo" wire:model.defer="cost"
                    wire:keydown.enter="addBrand"></x-base-input>
                <x-input-error for="cost">Requerido</x-input-error>
            </div>
        </div>
    </form>
    <x-input-error for="brands">Agregue un atributo</x-input-error>
    <div class="py-4">
        @foreach ($brands as $id=> $brand)
            <div
                class="relative flex items-center space-x-4 w-full px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="fas fa-times text-red-400" wire:click="removeBrand('{{$id}}')"></span>
                <div class=" w-full grid grid-cols-3  items-center">
                    <span>{{ $brand['name'] }}</span>
                    <span>{{ $brand['cant'] }}</span>
                    <h1 class="text-right">${{ formatNumber($brand['cost']) }}</h1>
                </div>
            </div>
          
        @endforeach
    </div>
</div>
