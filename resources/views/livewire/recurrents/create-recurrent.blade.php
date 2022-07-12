<div>
    <x-modal  :fitV="false" title="Registro de nueva obligación">
        <x-slot name="button">
            Nueva obligación
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="createRecurrent">
                <div class="flex flex-col space-y-4">
                    <div class="flex space-x-4">
                        <div class="w-full">
                            <x-base-input id="formRecId" wire:model.defer="form.name" label="Nombre de la obligación">
                            </x-base-input>
                        </div>
                    </div>
                   
                    <div class="flex space-x-4">
                        <div class="w-full">
                            <x-base-input type="number" wire:model.defer="form.amount" id="formAmntId"
                                label="Monto a pagar"></x-base-input>
                        </div>
                        <div class="w-full">
                            <x-base-select wire:model.defer="form.recurrency" id="formRecyId"
                                label="Recurrencia del pago">
                                <option>Semanal</option>
                                <option>Quincenal</option>
                                <option>Mensual</option>
                                <option>Anual</option>
                            </x-base-select>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="w-full">
                            <x-base-input type="date" wire:model.defer="form.expires_at" id="formExpId"
                                label="Próx. Pago"></x-base-input>
                        </div>
                        <div class="w-full">
                            <x-datalist model="form.count_id" inputId="formCntyId" listName="countList" label="Cuenta Afectada" placeholder="Seleccione una cuenta">
                                @foreach ($counts as $id => $name)
                                    <option value="{{ $name }}" data-value="{{ $id }}"></option>
                                    
                                @endforeach
                            </x-datalist>
                        </div>
                    </div>
                    <div class="flex justify-end py-4">
                        <x-button>Guardar</x-button>
                    </div>
                </div>
            </form>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error</strong>
                    <span class="block sm:inline">
                        Revise todos los campos
                    </span>
            @endif
        </div>
    </x-modal>
</div>
