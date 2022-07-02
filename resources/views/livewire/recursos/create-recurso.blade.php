<div>
    <x-modal maxWidth="max-w-lg" :fitV="false">
        <x-slot name="button">
            <span> Crear</span>
        </x-slot>
        <x-slot name="title">Registrar recurso/Condimento</x-slot>

        <div class="py-4">
            <form action="" wire:submit.prevent="createRecurso" class="space-y-4" id="createRecurso">
                <div>
                    <x-base-input label="Nombre del recurso" wire:model.defer="form.name" id="form.r.name">
                    </x-base-input>
                    <x-input-error for="form.name">Verifique el campo</x-input-error>
                </div>
                <div class="flex space-x-4">
                    <div class="w-full">
                        <x-base-select label="Tipo" wire:model="type" id="r.type">
                            <option value="Recurso">Recurso</option>
                            <option value="Condimento">Condimento</option>
                        </x-base-select>
                        <x-input-error for="form.type">Verifique el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist label="Proveedor" model="form.provider_id" listName="providerList"
                            inputId="formProvider_id">
                            @foreach ($providers as $id => $provider)
                                <option data-value="{{ $id }}" value="{{ $provider }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="form.provider_id">Verifique el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist label="Medida" listName="unitList" model="form.unit_id" inputId="formUnit_id">
                            @foreach ($units as $id => $unit)
                                <option data-value="{{ $id }}" value="{{ $unit }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="form.unit_id">Verifique el campo</x-input-error>
                    </div>
                   
                </div>
              

            </form>
            <div class="py-4">
                @if ($type == 'Recurso')
                    @include('livewire.recursos.includes.brandsection')
                @else
                    <div class="w-1/4">
                        <x-base-input type="number" id="cost" label="Costo" wire:model.defer="cost">
                        </x-base-input>
                        <x-input-error for="cost">Requerido</x-input-error>
                    </div>
                @endif
            </div>
            <div class="flex justify-end mt-8">
                <div class="">
                    <x-button form="createRecurso"
                        class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
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
