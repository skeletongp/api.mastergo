<div class="px-4">
    <form action="" wire:submit.prevent="createComprobante">

        <h1 class="font-semibold text-left uppercase mb-6">Registrar nuevos comprobantes</h1>
        <div class="flex space-x-4 items-end">
            <div class="w-full">
                <x-select wire:model.defer="form.type" id="comprobante.type">
                    <option value="" class="text-gray-300">SELECCIONE UN TIPO</option>
                    @foreach (App\Models\Invoice::TYPES as $name => $type)
                        @if ($type !== 'B00')
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endif
                    @endforeach
                </x-select>
                <x-input-error for="form.type"></x-input-error>
            </div>
            <div  >
                <x-input label="Inicial" type="number" wire:model.defer="form.inicial" id="comprobante.inicial">
                </x-input>
                <x-input-error for="form.inicial"></x-input-error>
            </div>
            <div  >
                <x-input label="Final" type="number" wire:model.defer="form.final" id="comprobante.final">
                </x-input>
                <x-input-error for="form.final"></x-input-error>
            </div>
            <div class="">
                <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                    wire:loading.attr='disabled'>
                    <div class="animate-spin mr-2" wire:loading wire:target="createComprobante">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    <span>Guardar</span>
                </x-button>
            </div>
        </div>

    </form>
</div>
