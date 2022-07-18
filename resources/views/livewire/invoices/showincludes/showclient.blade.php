<div class="w-full max-w-xl mx-auto pt-8 space-y-4">
    <div class="flex space-x-4 items-end">
        <div class="w-full">
            <x-datalist value="" listName="cltList" model="client_code" inputId="cltCodeId" label="Camabiar cliente" placeholder="Seleccione nuevo cliente">
                @foreach ($clients as $code => $name)
                    <option value="{{$code.' - '. $name }}" data-value="{{$code}}"></option>
                @endforeach
            </x-datalist>
        </div>
        <div class="w-full">
            <x-base-input disabled label="Cliente Actual" value="{{$invoice['name']?:($client['name']?:$client['contact']['fullname'])}}"></x-base-input>
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
