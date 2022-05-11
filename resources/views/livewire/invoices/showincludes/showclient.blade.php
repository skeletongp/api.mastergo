<div class="w-full space-y-2">
    <div class="flex space-x-4">
        <div class="flex space-x-2 relative">
            <x-base-input class="text-sm uppercase" inputClass="py-1" wire:model.lazy="client_code" id="client_code"
                label="Cód. Cliente" type="number"></x-base-input>
            <div class="absolute top-0 h-full right-0 ">
                <span wire:click="changeClient" class="fas fa-search cursor-pointer text-cyan-600"></span>
            </div>
        </div>
        <div class="w-full">
            <x-base-select label="Nombre Completo" class="text-sm uppercase py-0 select2" wire:model="client_code">
                <option value="">Seleccione un Cliente</option>
                @foreach ($clients as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </x-base-select>
        </div>
    </div>
    <div class="w-full">
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
