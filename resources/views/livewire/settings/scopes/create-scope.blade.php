<div>
    <h1 class="uppercase text-xl text-center font-bold">Crear Scope</h1>
    <div class="my-4 px-4">
        <form action="" class="mt-8" wire:submit.prevent="createScope">
            <div class="flex justify-between items-end space-x-4">
                <div class="w-full max-w-xs">
                    <x-input label="Nombre del Scope" wire:model.defer="form.name" id="form.scope.name"></x-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div class="">
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled'>
                        <div class="animate-spin mr-2" wire:loading wire:target="createScope">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        <span>Guardar</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
