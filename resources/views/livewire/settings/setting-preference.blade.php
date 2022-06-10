<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">

    @can('Crear Sucursales')
        <div class="flex flex-col  space-y-2 relative">
            <h1 class="p-4 text-xl font-bold uppercase">Preferencias</h1>
            <form action="" class=" p-2" wire:submit.prevent="updatePreference">
                <p class="my-4">En esta sección puedes configurar los valores predeterminados correspondientes a
                    esta sucursal, tales como: impresora predeterminada, tipo de comprobante más utilizado, etc. Si no aparece la lista de impresoras instaladas, presione Ctrl+R y espere 5 segundos.</p>
                <div class="w-full space-y-3  flex items-center ">
                    <div class="space-y-3 w-full">
                        <div class="flex space-x-4 items-center">
                            <div class="w-full max-w-sm">
                                <x-base-select label="Tipo de Comprobante" wire:model="preference.comprobante_type"
                                    id="comprobante_type">
                                    @foreach (\App\Models\Invoice::TYPES as $id => $type)
                                        <option value="{{ $type }}">{{ $type }} - {{ $id }}
                                        </option>
                                    @endforeach
                                </x-base-select>
                                <x-input-error for="preference.comprobante_type"></x-input-error>
                            </div>
                            <div class="">
                                <x-base-select label="Medida" wire:model="preference.unit_id" id="unit_id">
                                    @foreach ($place->store->units->pluck('name', 'id') as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}
                                        </option>
                                    @endforeach
                                </x-base-select>
                                <x-input-error for="preference.unit_id"></x-input-error>
                            </div>
                            <div class="">
                                <x-base-select label="Impuestos" wire:model="preference.tax_id" id="tax_id">
                                    @foreach ($place->store->taxes->pluck('name', 'id') as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}
                                        </option>
                                    @endforeach
                                </x-base-select>
                                <x-input-error for="preference.tax_id"></x-input-error>
                            </div>
                            <div class="">
                                <x-base-input label="Copias" wire:model.defer="preference.copy_print" type="number">
                                </x-base-input>
                                <x-input-error for="preference.copy_print"></x-input-error>
                            </div>
                        </div>
                        <div class="w-full max-w-sm">
                            <x-base-select wire:model="preference.printer" id="printer">
                                <x-slot name="label">
                                    <div class="flex justify-between items-center" id="searchPrinter">
                                        <span class="">
                                            Impresora predeterminada</span>
                                        <span class="fas fa-search text-blue-400 cursor-pointer"></span>
                                    </div>
                                </x-slot>
                                @foreach ($printers as $printer)
                                    <option>{{ $printer }}
                                    </option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="preference.tax_id"></x-input-error>
                        </div>
                        {{-- Botón --}}
                        <div class="flex my-4  items-center justify-end ">
                            <x-button
                                class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                                wire:loading.attr="disabled">
                                <div class="animate-spin mr-2" wire:loading wire:target="updatepreference">
                                    <span class="fa fa-spinner ">
                                    </span>
                                </div>
                                Actualizar
                            </x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
    @push('js')
        <script>
            $(document).ready(function() {
                function searchPrinter() {
                    ConectorPlugin.obtenerImpresoras()
                        .then(impresoras => {
                            @this.printers = impresoras;
                        });
                }
                $('#searchPrinter').on('click', function() {
                    searchPrinter()
                });
                searchPrinter()
            })
        </script>
    @endpush
</div>
