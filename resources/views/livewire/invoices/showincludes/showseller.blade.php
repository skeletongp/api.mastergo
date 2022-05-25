<div class="w-full max-w-xl mx-auto pt-8 space-y-4">
    <div class="flex space-x-4 items-end">
        <div class="w-full ">
            <x-base-input class="text-sm uppercase" wire:model.lazy="seller.fullname" id="sllr.fullname" label="Nombre del vendedor"
                status="disabled"></x-base-input>
        </div>
       
    </div>
    <div class="w-full flex space-x-2">
        <div class="w-full">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="seller.email"
            id="sllr.email" label="Correo Electrónico"></x-base-input>
        </div>
        <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="seller.phone"
            id="sllr.phone" label="Nº. Teléfono" type="tel"></x-base-input>
    </div>
</div>
