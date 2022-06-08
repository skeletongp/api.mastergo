<div>
    <x-modal maxWidth="max-w-lg" >
        <x-slot name="button">
            <span> Crear</span>
        </x-slot>
        <x-slot name="title">Registrar recurso</x-slot>

        <div class="py-4">
            <form action="" wire:submit.prevent="createRecurso" class="space-y-4" id="createRecurso">
                <div>
                    <x-base-input label="Nombre del recurso" wire:model.defer="form.name" id="form.r.name">
                    </x-base-input>
                    <x-input-error for="form.name">Verifique el campo</x-input-error>
                </div>
                <div class="flex space-x-4">
                    <div class="w-full">
                        <x-select wire:model.defer="form.provider_id" id="form.r.provider_id">
                            <option value="">Proveedor</option>
                            @foreach ($providers as $id => $provider)
                                <option value="{{ $id }}">{{ $provider }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.provider_id">Verifique el campo</x-input-error>
                    </div>
                    <div class="w-1/3">
                        <x-select wire:model.defer="form.unit_id" id="form.r.unit_id">
                            <option value="">Medida</option>
                            @foreach ($units as $id => $unit)
                                <option value="{{ $id }}">{{ $unit }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.unit_id">Verifique el campo</x-input-error>
                    </div>
                </div>

            </form>
            <div class="py-4">
                @include('livewire.recursos.includes.brandsection')
            </div>
            <div class="flex justify-end mt-8">
                <div class="">
                    <x-button form="createRecurso" class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled'>
                        <div class="animate-spin mr-2" wire:loading wire:target="createRecurso">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        <span>Guardar</span>
                    </x-button>
                </div>
            </div>
        </div>
    </x-modal>

</div>
