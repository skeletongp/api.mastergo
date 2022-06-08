<div>
    <x-modal title="Nuevo Proceso" >
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
                <x-base-input inputClass="text-lg" id="form.proc.name" wire:model.defer="form.name" label="Nombre"></x-base-input>
            </form>
        </div>
    </x-modal>
</div>
