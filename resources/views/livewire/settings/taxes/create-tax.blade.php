<div class="px-4">
    <form action="" wire:submit.prevent="createTax">

        <h1 class="font-semibold text-left uppercase mb-6">Registrar nuevo impuesto</h1>
        <div class="flex space-x-4 items-end">
            <div>
                <x-input label="Nombre (ej. ITBIS)" wire:model.defer="form.name" id="tax.name">
                </x-input>
                <x-input-error for="form.name"></x-input-error>
            </div>
            <div>
                <x-input label="Tasa (%)" type="number" min="1" wire:model.defer="form.rate" id="tax.rate">
                </x-input>
                <x-input-error for="form.rate"></x-input-error>
            </div>
            <div class="">
                <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                    wire:loading.attr='disabled'>
                    <div class="animate-spin mr-2" wire:loading wire:target="createTax">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    <span>Guardar</span>
                </x-button>
            </div>
        </div>

    </form>
</div>
