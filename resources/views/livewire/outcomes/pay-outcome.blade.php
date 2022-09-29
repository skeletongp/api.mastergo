<div>
    <x-modal title="Pagos a cuenta" :fitV="true" :listenOpen="true" maxWidth="max-w-4xl">
        <x-slot name="button">
            <span class="fas fa-hand-holding-usd text-green-500">

            </span>
        </x-slot>
        <div class="text-left">
            @if ($outcome && $outcome->rest > 0)
                <form action="" wire:submit.prevent="payOutcome" class="max-w-2xl mx-auto">
                    <div class="py-4  flex space-x-4 items-start">
                        <div>
                            <x-base-input required
                                class="text-xl font-bold" type="number" wire:model.lazy="efectivo"
                                label="Efectivo" id="efectivo">
                            </x-base-input>
                            <x-input-error for="efectivo">Verifique el campo</x-input-error>
                        </div>
                        <div>
                            <x-base-input  required
                                class="text-xl font-bold" type="number" wire:model.lazy="tarjeta"
                                label="Tarjeta/Cheque" id="tarjeta">
                            </x-base-input>
                            <x-input-error for="tarjeta">Verifique el campo</x-input-error>
                        </div>
                        @if (count($banks))
                            <div>
                                <x-base-input  required
                                    class="text-xl font-bold" type="number" wire:model.lazy="transferencia"
                                    label="Transferencia" id="transferencia">
                                </x-base-input>
                                <x-input-error for="transferencia">Verifique el campo</x-input-error>
                            </div>
                        @endif
                        <div class=" pt-10 bottom-0">
                            <x-button class="flex space-x-4" wire:loading.disabled>
                                <span>
                                    Cobrar
                                </span>
                            </x-button>
                        </div>

                    </div>
                    @if ($transferencia > 0)
                        <div class="flex space-x-4 items-start ">
                            <div class="w-full">
                                <x-base-select id="bank_id" wire:model="bank_id" label="Cuenta de Banco"
                                    class="py-3">
                                    <option value=""></option>
                                    @foreach ($banks as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </x-base-select>
                                <x-input-error for="bank_id">Seleccione un Banco</x-input-error>
                            </div>
                            <div class="w-full">
                                <x-base-input class="text-sm py-3" type="text" wire:model.defer="ref_bank"
                                    label="No. Referencia" id="reference" placeholder="Nº. Ref."></x-base-input>
                                <x-input-error for="ref_bank">Verifique el campo</x-input-error>
                            </div>

                        </div>
                    @endif
                    @if ($this->efectivo > 0)
                    <div class="flex space-x-4 items-start mt-8">
                        <div class="w-full">
                            <x-base-select label="Caja a Reducir" wire:model="efectivoCode" id="efectivoCode">
                                <option value=""></option>
                                @foreach ($efectivos as $index=> $efectivo)
                                    <option value="{{$index}}">{{$efectivo}}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="efectivoCode">Campo requerido</x-input-error>
                        </div>
                        <div class="w-full"></div>
                    </div>
                @endif
                </form>
            @endif
            @if ($outcome)
                <div class="py-4">
                    <livewire:outcomes.outcome-payments :outcome_id="$outcome->id" />

                </div>

            @endif
        </div>
    </x-modal>
</div>
