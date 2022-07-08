<div >
    <x-modal title="Nuevo Proceso" :fitV="false">
        <x-slot name="button">
            <div class="flex space-x-4 items-center">
                <span>Crear Proceso</span>
                <span class="fas fa-plus"></span>
            </div>
          
        </x-slot>
       
        <div>
            <form action="" wire:submit.prevent="authorize('Autorizar acción', 'validateAuthorization','storeProceso','','Crear Procesos')" class="text-lg">
                <div class="flex justify-end">
                    <span>
                        <b>Cod.: </b>{{$form['code']}}
                    </span>
                </div>
                <div class="flex space-x-4">
                    <div class="w-full">
                        <x-base-input class="py-1.5" inputClass="text-lg" id="form.proc.name" wire:model.defer="form.name" label="Nombre"></x-base-input>
                        <x-input-error for="form.name"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select label="Medida "  id="form.proc.unit_id" wire:model.defer="form.unit_id">
                            <option value=""></option>
                            @foreach ($units as $id=> $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="form.unit_id"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end py-4">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
