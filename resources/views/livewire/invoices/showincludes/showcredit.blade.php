<div >
    <x-modal :fitV='false' maxWidth="max-w-2xl mx-auto" :listenOpen="true">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Registrar Nota de Crédito</span>
            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-plus mr-2"></span>
            <span> Añadir</span>
        </x-slot>
        <div class="relative">
            <form action="" wire:submit.prevent="createCreditnote">
                <div class="w-full max-w-xl space-y-4">
                    <div class="w-full grid grid-cols-3 items-end  gap-4">
                        <div class="col-span-1">
                            <x-base-input class="text-base uppercase" wire:model.defer="credit.ncf" id="ncf"
                                type="text" disabled label="NCF"></x-base-input>
                        </div>
                        <div class="col-span-2 ">
                            <x-base-input type="text" class="text-sm " wire:model.lazy="credit.comment"
                                id="comment" label="Comentario/Nota">
                            </x-base-input>
                        </div>
                        <div class="col-span-1">
                            <x-base-input type="text" class="text-sm " wire:model.lazy="credit.amount" id="amount"
                                label="Mto. Descuento">
                            </x-base-input>
                        </div>
                        <div class="col-span-1">
                            <x-base-input type="text" class="text-sm " wire:model.lazy="credit.itbis" id="itbis"
                                label="ITBIS Desc.">
                            </x-base-input>
                        </div>
                        <div class="col-span-1">
                            <x-base-input type="text" class="text-sm " wire:model.lazy="credit.selectivo"
                                id="selectivo" label="Selectivo">
                            </x-base-input>
                        </div>
                        <div class="col-span-1">
                            <x-base-input type="text" class="text-sm " wire:model.lazy="credit.propina"
                                id="propina" label="Propina">
                            </x-base-input>
                        </div>
                        <div class="col-span-1">
                            <x-base-input type="text" class="text-sm " disabled wire:model.lazy="credit.modified_ncf"
                                id="modified_ncf" label="NCF. Original">
                            </x-base-input>
                        </div>
                        <div class="flex justify-center col-span-1 h-10">
                            <x-button class="w-full text-center">Registrar Nota</x-button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="w-full">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                <strong class="font-bold">Error</strong>
                                <span class="block sm:inline">
                                    Revise todos los campos
                                </span>
                            </div>
                        </div>
                    @endif

                </div>

            </form>
            {{--  @if ($invoice->creditnote)
                <div class="flex justify-end max-w-xl py-4">
                    <x-button wire:click="printCreditNote">Imprimir</x-button>
                </div>
            @endif --}}

        </div>



    </x-modal>
</div>

