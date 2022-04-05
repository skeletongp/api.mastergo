<div class="px-4">
    <form action="" wire:submit.prevent="createUnit">

        <h1 class="font-semibold text-left uppercase mb-6">Registrar nueva unidad</h1>
        <div class="flex space-x-4 items-end">
            <div>
                <x-input label="Nombre (ej. Libra)" wire:model.defer="form.name" id="unit.name">
                </x-input>
                <x-input-error for="form.name"></x-input-error>
            </div>
            <div>
                <x-input label="Símbolo (ej. LB)" wire:model.defer="form.symbol" id="unit.symbol">
                </x-input>
                <x-input-error for="form.symbol"></x-input-error>
            </div>
                <div class="">
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled'>
                        <div class="animate-spin mr-2" wire:loading wire:target="createUnit">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        <span>Guardar</span>
                    </x-button>
                </div>
        </div>

    </form>
</div>
