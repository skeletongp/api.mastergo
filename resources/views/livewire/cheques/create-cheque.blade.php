<div>
    <x-modal  :fitV="false" title="Registrar Cheque" maxWidth="max-w-2xl">
        <x-slot name="button">
            <span>Nuevo Cheque</span>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="createCheque">
                <div class="space-y-4 w-full">
                    <div class="flex space-x-4">
                        <div class="w-full">
                            <x-base-input type="text" label="Nº. Referencia" wire:model.defer="form.reference" id="chkref">
                            </x-base-input>
                            <x-input-error for="form.reference"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-base-select label="Banco" wire:model.defer="form.bank_id" id="chkbid">
                                <option value=""></option>
                                @foreach ($banks as $ide => $bank)
                                    <option value="{{ $ide }}">{{ $bank }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="form.bank_id"></x-input-error>
                        </div>
                    </div>
               
                    <div class="flex space-x-4">
                        <div class="w-full">
                            <x-base-input type="number" label="Monto del Cheque" wire:model.defer="form.amount" id="chkamnt">
                            </x-base-input>
                            <x-input-error for="form.amount"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-base-select label="Tipo de Cheque" wire:model.defer="form.type" id="chktp">
                                <option value=""></option>
                               <option value="Emitido">Cheque Emitido</option>
                               <option value="Recibido">Cheque Recibido</option>
                            </x-base-select>
                            <x-input-error for="form.type"></x-input-error>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <x-base-select label="Titular del Cheque" wire:model="person" id="chkprs">
                                <option value=""></option>
                              @foreach ($persons as $idi => $prsn)
                                    <option value="{{ $idi }}">{{ $prsn }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="person"></x-input-error>
                        </div>
                    </div>
                    <div class="flex justify-end p-4">
                        <x-button>Registrar</x-button>
                    </div>
                </div>
            </form>
        </div>
        <x-error-box>

        </x-error-box>
    </x-modal>
</div>
