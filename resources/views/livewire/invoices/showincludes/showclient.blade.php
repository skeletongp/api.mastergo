<div class="w-full max-w-xl mx-auto pt-8 space-y-4">
    <div class="flex space-x-4 items-end">
        <div class="flex space-x-2 relative ">
            <x-base-input class="text-sm uppercase" inputClass="py-1" wire:model.lazy="client_code" id="client_code"
                label="Cód. Cliente" type="number"
                status="{{ auth()->user()->hasPermissionTo('Editar Facturas')? '': 'disabled' }}"></x-base-input>
            <div class="absolute top-0 h-full right-0 ">
                <span wire:click="changeClient" class="fas fa-search cursor-pointer text-cyan-600"></span>
            </div>
        </div>
        <div class="w-full">
            <x-select-search :data="$clients" wire:model="client_code" :placeholder="'Nombre completo'" :wire:key="uniqid()" />

        </div>
    </div>
    <div class="w-full flex space-x-2">
        <div class="w-full">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.address"
                id="clt.address" label="Dirección"></x-base-input>
        </div>
        <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.phone"
            id="clt.phone" label="Nº. Teléfono" type="tel"></x-base-input>
    </div>
    <div class="flex space-x-2 ">
        <div class="">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.rnc"
                id="clt.rnc" label="RNC/CÉD."></x-base-input>
        </div>
        <div class="w-full">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.email"
                id="clt.email" label="Correo Electrónico"></x-base-input>
        </div>

    </div>
    <div class="flex space-x-2 justify-between items-end">
        <div class="">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.balance"
                id="clt.balance" label="Balance"></x-base-input>
        </div>
        <div class="">
            <x-base-input class="text-base uppercase" inputClass="py-2" disabled wire:model.defer="client.gasto"
                id="clt.gasto" label="Total Gastado"></x-base-input>
        </div>
        <div class="h-full flex pb-[0.34rem]">
            <x-button wire:click="tryUpdateInvoiceClient">
                Actualizar
            </x-button>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Seleccione un cliente",
                    allowClear: true
                });
                $('.select2').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('client_code', data);
                });
            });
            Livewire.hook('element.updated', function() {
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Seleccione un cliente",
                        allowClear: true
                    });
                });
                $('.select2').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('client_code', data);
                });
            });
        </script>
    @endpush
</div>
