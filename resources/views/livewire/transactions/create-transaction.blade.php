<div>
    <x-modal maxWidth="max-w-2xl" title="Nuevo Asiento Contable" :fitV="false">
        <x-slot name="button">
            Añadir Asiento
        </x-slot>

        <div>
            <form
                wire:submit.prevent="createTransaction">
                @php
                    
                    asort($countMains)
                @endphp
                <h1 class="text-lg font-bold uppercase my-2">Débito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist inputId="cMainDebit_id" listName="cMainDebit_idList" model="cMainDebit_id" label="Cuenta Control">
                            @foreach ($countMains as $id => $cMain)
                                <option data-value="{{ $id }}" value="{{ $cMain }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cMainDebit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist inputId="cDetailDebit_id" model="cDetailDebit_id" listName="cDetailDebit_idList" label="Cuenta Detalle">
                            @foreach ($countsDebit as $idDebit => $cDebit)
                                <option data-value="{{ $idDebit }}" value="{{ $cDebit }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cDetailDebit_id"></x-input-error>
                    </div>
                </div>
                <h1 class="text-lg font-bold uppercase my-2 mt-4">Crédito</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist inputId="cMainCredit_id" listName="cMainCredit_idList" model="cMainCredit_id" label="Cuenta Control">
                            @foreach ($countMains as $id => $cMain)
                                <option data-value="{{ $id }}" value="{{ $cMain }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="cMainCredit_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist inputId="cDetailCredit_id" listName="cDetailCredit_idList" model="cDetailCredit_id" label="Cuenta Detalle">
                            @foreach ($countsCredit as $idCredit => $cCredit)
                                <option data-value="{{ $idCredit }}" value="{{ $cCredit }}"></option>
                            @endforeach
                        </x-datalist>
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
