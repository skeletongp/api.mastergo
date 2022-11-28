<div>
    <x-modal Title="Anular Comprobante" maxWidth="max-w-2xl" :listenOpen="true">

        <x-slot name="button">
            <span class="fas fa-ban text-red-400"></span>
        </x-slot>
        <div class="flex flex-col space-y-4 relative pt-8">
            <div class="absolute top-2 right-2">
                <x-toggle label="¿Anular factura?" id="anulateFactura{{ $comprobante_id }}" wire:model="anulateFactura"></x-toggle>
            </div>
            <div class="flex space-x-4 items-end">
                <div class="w-1/3">
                    <x-base-input id="ncf{{ $comprobante_id }}" label="NCF" disabled value="{{ optional($comprobante)->ncf }}"></x-base-input>
                </div>
                <div class="w-1/3">
                    <x-base-input id="fecha{{ $comprobante_id }}" label="Fecha" disabled value="{{ formatDate(optional($invoice)->day, 'Ymd') }}">
                    </x-base-input>
                </div>
                <div class="w-1/3">
                    <x-base-input id="cliente{{ $comprobante_id }}" label="Cliente" disabled value="{{ ellipsis(optional($invoice)->name, 22) }}">
                    </x-base-input>
                </div>
            </div>
            <div class="flex justify-between space-x-4 items-end">
                <div class="w-1/2 ">
                    <x-base-select wire:model="motivo" class="w-full" label="Motivo de anulación" id="motivo{{ $comprobante_id }}">
                        <option value=""></option>
                        @foreach (App\Models\Comprobante::MOTIVOS as $index=> $motivo)
                            <option value="{{$index}}">{{$motivo}}</option>
                        @endforeach
                    </x-base-select>

                </div>
                <div class="w-1/3 pl-4">
                    <x-button wire:click="anulate" class="w-full !text-center py-3">
                        <h1 class="text-bse w-full">
                            Anular
                        </h1>
                    </x-button>
                </div>
            </div>
            <div>
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif
            </div>
        </div>
    </x-modal>
</div>
