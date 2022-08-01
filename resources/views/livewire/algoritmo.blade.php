<div class="w-full max-w-2xl">
    <div class="flex space-x-4">
        <div class="w-full">
            <x-base-input class="w-full" label="Cadena de Texto" wire:model.defer="cadena"></x-base-input>
            <x-input-error for="cadena">Texto requerido</x-input-error>
        </div>

    </div>
    <h1 class="font-bold uppercase text-xl py-4">¿Qué desea hacer?</h1>
    <div class="flex space-x-4">
        <x-button wire:click="operarString('comparar', 'Ingrese el segundo valor')">Comparar</x-button>
        <x-button wire:click="medir">Medir</x-button>
        <x-button wire:click="operarString('buscar', 'Ingrese el segundo valor')">Buscar</x-button>
    </div>
    <div class="p-4">
        @if ($result)
            <div class="bg-red-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Resultado</p>
                <p>
                <ul>
                    <li>{{ $result }}</li>
                   

                </ul>
                </p>
            </div>

        @endif
    </div>
</div>
