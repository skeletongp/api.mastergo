<div class="w-full max-w-xl mx-auto pt-8 space-y-4">
    <div class="flex space-x-4 items-end">
        <div class="w-full ">
            <x-base-input class="text-sm uppercase" wire:model.lazy="contable.fullname" id="cntbl.fullname" label="Nombre del cajero"
                status="disabled"></x-base-input>
        </div>
       
    </div>
    <div class="w-full flex space-x-2">
        <div class="w-full">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="contable.email"
            id="cntbl.email" label="Correo Electrónico"></x-base-input>
        </div>
        <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="contable.phone"
            id="cntbl.phone" label="Nº. Teléfono" type="tel"></x-base-input>
    </div>
</div>
