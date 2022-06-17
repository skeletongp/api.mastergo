<div>
    <x-modal :fitV="false">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <span> Gestionar cuadre</span>
            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-cash-register mr-2"></span>
            <span> Cuadre</span>
        </x-slot>
        <div class="relative">
            <form action="" wire:loading.disabled
                wire:submit.prevent="authorize('¿Permitir gestionar cuadre?', 'validateAuthorization','openCuadre',null,'Abrir Cuadre')">
                <div class="p-2 px-4 pb-4 shadow-lg flex space-x-4 items-end">
                    <div class="w-full">
                        <x-base-input label="Monto Retirado" id="mntRetirado" wire:model.defer="retirado" type="number"
                            placeholder="Dinero que se retira de la caja" />
                    </div>
                    <div class="">
                        <x-button wire:loading.disabled
                            wire:click="authorize('¿Permitir gestionar cuadre?', 'validateAuthorization','openCuadre',null,'Abrir Cuadre')">
                            Cuadrar</x-button>
                    </div>

                </div>
                <x-input-error for="retirado">Monto Requerido</x-input-error>
            </form>
        </div>
    </x-modal>

</div>
