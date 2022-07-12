<div>
    <form action="" wire:submit.prevent="createCreditnote">
        <div class="w-full max-w-xl space-y-4">
            <h1 class="font-bold py-4 uppercase">Nota de Crédito</h1>
            <div class="w-full flex space-x-2">
                <div class="w-full">
                    <x-base-input class="text-base uppercase" inputClass="py-2" wire:model.defer="modified_ncf"
                        id="modified_at" type="text" disabled label="NCF Nota de Crédito"></x-base-input>
                </div>
                <div class="w-full">
                    <x-base-input status="{{$invoice->creditnote?'disabled':''}}" class="text-base uppercase" inputClass="py-2" wire:model.defer="modified_at"
                        id="modified_at" label="Fecha" type="date"></x-base-input>
                </div>
                <div class="w-full">
                    <x-base-input disabled class="text-base uppercase" inputClass="py-2" value="{{$invoice->creditnote?$invoice->creditnote->user->fullname:auth()->user()->fullname}}"
                        id="userResp" label="Elaborada por" type="text"></x-base-input>
                </div>
            </div>
            <div class="flex space-x-4 items-end">
                <div class="w-full ">
                    <x-base-input status="{{$invoice->creditnote?'disabled':''}}" type="text" class="text-sm " wire:model.lazy="comment" id="comment"
                        label="Comentario">
                    </x-base-input>
                </div>
            </div>
            @if (!$invoice->creditNote)
                <div class="flex justify-end">
                    <x-button>Crear</x-button>
                </div>
            @endif
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

</div>
