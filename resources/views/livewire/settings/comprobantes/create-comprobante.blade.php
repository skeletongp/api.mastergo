<div class="px-4">
    <form action="" wire:submit.prevent="createComprobante">

        <div class="flex space-x-4 items-start py-4">
            <div class="w-full">
                <x-base-select wire:model.defer="form.type" id="comprobante.type" label="Tipo">
                    <option value="" class="text-gray-300"></option>
                    @foreach (App\Models\Invoice::TYPES as $name => $type)
                        @if ($type !== 'B00')
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endif
                    @endforeach
                </x-base-select>
                <x-input-error for="form.type">Requerido</x-input-error>
            </div>
            <div  >
                <x-base-input label="Inicial" type="number" wire:model.defer="form.inicial" id="comprobante.inicial">
                </x-base-input>
                <x-input-error for="form.inicial">Requerido</x-input-error>
            </div>
            <div  >
                <x-base-input label="Final" type="number" wire:model.defer="form.final" id="comprobante.final">
                </x-base-input>
                <x-input-error for="form.final">Requerido</x-input-error>
            </div>
            <div class="pt-8">
                <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                    wire:loading.attr='disabled'>
                    <div class="animate-spin mr-2" wire:loading wire:target="createComprobante">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    <span  wire:loading.attr='hidden'>Guardar</span>
                    <span  wire:loading>Guardando</span>
                </x-button>
            </div>
        </div>

    </form>
</div>
