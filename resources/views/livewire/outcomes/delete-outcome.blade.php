<div>
    <x-modal title="Anular Gasto" :listenOpen="true">
        <x-slot name="button">
            <i class="far fa-trash-alt text-red-400"></i>
        </x-slot>
        <div>
            <form action="" id="delete{{ $outcome_id }}" wire:submit.prevent="deleteOutcome" class="space-y-4 text-left">
                <div class="flex space-x-4 items-start">
                   ¿Seguro desea borrar este gasto?
                </div>

                <div class="w-full flex justify-end" form="delete{{ $outcome_id }}">
                    <x-button class="bg-red-400 text-white">
                        Anular
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
