<div>
    <x-modal title="Nueva producción" >
        <x-slot name='button'>
            <div class="flex space-x-4 items-center">
                <span>Añadir producción</span>
                <span class="fas fa-plus"></span>
            </div>
        </x-slot>
        <div>
           
            <form action="" wire:submit.prevent="authorize('Autorizar acción', 'validateAuthorization','storeProduction','','Crear Producciones')">
                <div class="flex space-x-4">
                    <div class="w-full">
                        <x-base-select wire:model='unit_id' label='Unidad' id="cr.unit_id">
                            @foreach ($units as $id=> $unit)
                                <option value="{{$id}}">{{$unit}}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for='unit_id'>Seleccione una unidad</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input label='Invertido' type='number' wire:model.defer='form.setted' id="cr.setted"></x-base-input>
                        <x-input-error for='form.setted'>Requerido</x-input-error>

                    </div>
                    <div class="w-full">
                        <x-base-input label='Inicio' type='datetime-local' wire:model.defer='form.start_at' id="cr.start_at "></x-base-input>
                        <x-input-error for='form.start_at'>Requerido</x-input-error>
                    </div>
                </div>
                <div class="flex justify-end py-4">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
