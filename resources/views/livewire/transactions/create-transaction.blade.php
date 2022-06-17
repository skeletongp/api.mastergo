<div>
    <x-modal maxWidth="max-w-2xl" title="Nuevo Asiento Contable" :fitV="false">
        <x-slot name="button">
            Añadir
        </x-slot>

        <div>
            <form
                wire:submit.prevent="authorize('¿Autoriza crear esta transacción?', 'validateAuthorization','createTransaction',null,'Crear Transacciones')">

                <h1 class="text-lg font-bold uppercase my-2">Débito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-select id="cMainDebit_id" wire:model="cMainDebit_id" label="Cuenta Principal">
                            <option></option>
                            @foreach ($countMains as $id => $cMain)
                                <option value="{{ $id }}">{{ $cMain }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="cMainDebit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select id="cDetailDebit_id" wire:model="cDetailDebit_id" label="Cuenta Detalle">
                            <option></option>
                            @foreach ($countsDebit as $idDebit => $cDebit)
                                <option value="{{ $idDebit }}">{{ $cDebit }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="cDetailDebit_id"></x-input-error>
                    </div>
                </div>
                <h1 class="text-lg font-bold uppercase my-2 mt-4">Crédito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-select id="cMainCredit_id" wire:model="cMainCredit_id" label="Cuenta Principal">
                            <option></option>
                            @foreach ($countMains as $id => $cMain)
                                <option value="{{ $id }}">{{ $cMain }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="cMainCredit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select id="cDetailCredit_id" wire:model="cDetailCredit_id" label="Cuenta Detalle">
                            <option></option>
                            @foreach ($countsCredit as $idCredit => $cCredit)
                                <option value="{{ $idCredit }}">{{ $cCredit }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="cDetailCredit_id"></x-input-error>
                    </div>
                </div>
                <div class="my-4">
                    <x-base-input id="trConcept" label="Concepto" wire:model.defer="concept" />
                    <x-input-error for="concept"></x-input-error>
                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input id="trRef" label="Referencia" wire:model.defer="ref" />
                        <x-input-error for="ref"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input id="trAmount" label="Monto" wire:model.defer="amount" type="number" />
                        <x-input-error for="amount"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end my-2">
                    <x-button>Registrar</x-button>
                </div>

            </form>
        </div>
    </x-modal>
</div>
