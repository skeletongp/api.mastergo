<div>
    <x-modal title="Gestionar producción" >
        <x-slot name="button">
            <span class="far fa-pen text-xl"></span>
        </x-slot>
        <form action="" wire:submit.prevent="updateProduction">
            <div class="p-4">
                <div class="flex space-x-4 items-start">
                    <div>
                        <x-base-input id="edit..{{$production['id']}}.setted" wire:model.defer="production.setted" label="Invertido">
                        </x-base-input>
                    </div>
                    <div>
                        <x-base-input id="edit..{{$production['id']}}.getted" wire:model.defer="production.getted" label="Obtenido">
                        </x-base-input>
                    </div>
                    <div class="">
                        <label 
                            class="block text-base pb-4 font-medium text-gray-900 dark:text-gray">Estado</label>
                        <x-toggle id="production.{{$production['id']}}.status" label="{{ $status?'Completado':'Iniciado' }}" wire:model="status" value="Completado"></x-toggle>
                    </div>
                </div>
                <div class="flex justify-end py-4">
                    <x-button>Actualizar</x-button>
                </div>
            </div>

        </form>
    </x-modal>
</div>
