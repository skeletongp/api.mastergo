<div class="w-full space-y-2">
    <div class="flex space-x-4 items-end">
        <div class="flex space-x-2 relative ">
            <x-base-input class="text-sm uppercase" inputClass="py-1" wire:model.lazy="client_code" id="client_code"
                label="Cód. Cliente" type="number"></x-base-input>
            <div class="absolute top-0 h-full right-0 ">
                <span wire:click="changeClient" class="fas fa-search cursor-pointer text-cyan-600"></span>
            </div>
        </div>
        <div>
            <x-datalist class="py-1" listName="clientListInvoice" inputId="clientInvoiceID" wire:model.lazy="clientNameCode" wire:keydown.enter.prevent="$emit('focusCodde')">
                @foreach ($clients as $index=> $clte)
                    <option value="{{$index.' - '.$clte}}" ></option>
                @endforeach
            </x-datalist>
        </div>

    </div>
   
    <div class="{{auth()->user()->store->generic->code==$client_code?'':'hidden'}}">
        <x-base-input wire:keydown.enter.prevent='rncEnter' wire:model.defer="name" placeholder="Cliente Genérico" class="py-1" label="Nombre/RNC" id="clt.inv.name">
        </x-base-input>
    </div>
    <div class="w-full  ">
        <x-base-input class="text-base uppercase" inputClass="py-0" disabled wire:model.defer="client.address"
            id="clt.address" label="Dirección"></x-base-input>
    </div>
    <div class="flex space-x-2 ">
        <div class="w- ">
            <x-base-input class="text-base uppercase" inputClass="py-0" disabled wire:model.defer="client.phone"
                id="clt.phone" label="Nº. Teléfono" type="tel"></x-base-input>
        </div>
        <div class="w-2/5">
            <x-base-input class="text-base uppercase" inputClass="py-0" disabled wire:model.defer="client.rnc"
                id="clt.rnc" label="RNC/CÉD."></x-base-input>
        </div>
        <div class="w-2/5">
            <x-base-input class="text-base uppercase" inputClass="py-0" disabled wire:model.defer="client.balance"
                id="clt.balance" label="Balance"></x-base-input>
        </div>
    </div>

</div>
