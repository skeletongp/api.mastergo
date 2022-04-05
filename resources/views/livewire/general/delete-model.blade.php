<div class="">
    <x-modal>
        <x-slot name="title">
            <h1 class="text-center font-bold uppercase text-xl">Eliminar registro</h1>
        </x-slot>
        <x-slot name="button">
            <span class="fas fa-trash-alt text-red-500" data-tooltip-target="deleteId{{$model->id}}"
                data-tooltip-style="light"></span>
                <x-tooltip id="deleteId{{$model->id}}"> Eliminar registro </x-tooltip>
        </x-slot>
        <div>
            <div class="w-full mt-4 text-center text-xl mb-2" >
                ¿Desea borrar este {{ $title }}?
            </div>
            <hr>
            <div class="my-3 flex justify-between px-8" wire:loading.class="hidden">
                <x-button class="bg-blue-600 text-white" wire:click.prevent="deleteModel" x-on:click="open = ! open">
                    <span class="text-white font-bold uppercase hover:text-gray-700">
                        Aceptar
                    </span>
                </x-button>
                <x-button class="bg-red-600 text-white" x-on:click.prevent="open = ! open">
                    <span class="text-white font-bold uppercase hover:text-gray-700">
                        Cancelar
                    </span>
                </x-button>
            </div>
            <div class="w-full ">
                <x-button class="space-x-2 z-50 text-sm flex justify-center items-center w-full mx-auto" wire:loading>
                    <div class="animate-spin">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    <h1>Procesando</h1>
                </x-button>
            </div>
         </div>
    </x-modal>    
</div>