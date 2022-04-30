<div class="w-full  max-w-7xl mx-auto">
    <h1 class="text-left font-bold uppercase my-6 p-4 text-2xl">Registrar proceso de producción</h1>
    <div class="flex space-x-4">
        {{-- Proceso --}}
        <div class=" space-y-4 p-4 w-full mx-auto">
            <h1 class="text-left uppercase text-xl font-bold my-2 ">Detalles del proceso</h1>
            <form action="" id="fCrearProceso" wire:submit.prevent="createProceso" class="space-y-4 w-full">
                <div class="flex space-x-4 items-start w-full ">
                    <div class="w-full">
                        <x-input label="Nombre" wire:model.lazy="form.name" id="form.proccess.name"></x-input>
                        <x-input-error for="form.name"></x-input-error>
                    </div>
                    <div>
                        <x-input type="date" label="Fecha Inicio" wire:model.lazy="form.start_at"
                            id="form.proccess.start_at"></x-input>
                        <x-input-error for="form.start_at">Elija una fecha</x-input-error>
                    </div>
                    <div class="flex justify-end mt-4 max-w-xl mx-auto">
                        <x-button form="fCrearProceso" class="bg-gray-100 ">
                            <span class="far fa-save text-gray-800 text-xl"></span>
                        </x-button>
                    </div>
                </div>
            </form>
            {{-- Recursos --}}
            @include('livewire.procesos.includes.div-recursos')
        </div>
        {{-- Requerimientos --}}
        <div class=" items-start px-6 w-full">
            
            {{-- Productos --}}
            @include('livewire.procesos.includes.div-products')
        </div>

    </div>
</div>
