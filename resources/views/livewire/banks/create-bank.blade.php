<div>
    <x-modal  :fitV="false"  title="Nueva Cuenta de Banco"  maxWidth="max-w-xl">
        <x-slot name="button">
            Nuevo Banco
        </x-slot>
        <div class="w-full">
            <form action="" wire:submit.prevent="createBank">
                <div class="flex flex-col space-y-4">
                    <div class="flex space-x-4 items-start">
                        <div class="w-full">
                            <x-datalist wire:model.defer="form.bank_name" listName="bankList" label="Nombre del banco" inputId="bankListId">
                                @foreach ($banks as $item)
                                    <option value="{{ $item}}"></option>
                                @endforeach

                            </x-datalist>
                            
                            <x-input-error for="form.bank_name">Requerido/Único</x-input-error>
                        </div>
                        <div class="w-full">
                            <x-base-input type="number" wire:model.defer="form.bank_number" label="Número de la cuenta" id="bank_number" />
                            <x-input-error for="form.bank_number">Campo requerido</x-input-error>
                        </div>
                    </div>
                    <div class="flex space-x-4 items-start">
                        <div class="w-full">
                            <x-datalist wire:model.defer="form.titular" listName="titularList" label="Nombre del titular" inputId="titularListId">
                                @foreach ($users as $item)
                                    <option value="{{ $item}}"></option>
                                @endforeach

                            </x-datalist>
                            <x-input-error for="form.titular">Revise el campo. Máx. 30</x-input-error>
                        </div>
                        <div class="w-full">
                            <x-base-select label="Moneda/Divisa" wire:model.defer="form.currency" id="bank_currency" >
                                
                                <option value="DOP">Peso Dominicano</option>
                                <option value="USD">Dólar Americano</option>
                            </x-base-select>
                            <x-input-error for="form.bank_currency">Campo requerido</x-input-error>
                        </div>
                    </div>
                    <div class="flex justify-end py-4">
                        <x-button>Registrar</x-button>
                    </div>
                </div>

            </form>
        </div>
    </x-modal>
</div>
