<div>
    <div class="w-full flex  items-start">
        <div class="p-8 max-w-6xl">
            <h1 class="font-bold p-4 uppercase text-xl">
                Filtrar
            </h1>
            <div class="flex space-x-4">
                <div class="w-full">
                    <x-base-input type="date" label="Desde" wire:model.defer="start_at"></x-base-input>
                </div>
                <div class="w-full">
                    <x-base-input type="date" label="Hasta" wire:model.defer="end_at"></x-base-input>
                </div>
            </div>
            <div class="py-4 flex justify-end">
                <x-button wire:click="changeDate">Actualizar</x-button>
            </div>
        </div>
        <div wire:loading id="generalReport607Load">
            <x-loading></x-loading>
        </div>
        <div class=" mx-auto relative w-full">
            @if ($url)
            <object class="mx-auto" data="data:application/pdf;base64,{{$url}}" type="application/pdf" width="700" height="700"></object>
            @endif
        </div>
    </div>
</div>
