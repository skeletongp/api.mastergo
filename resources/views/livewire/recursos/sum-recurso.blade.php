    <div class="p-4 shadow-xl max-w-6xl w-full flex space-x-6 mx-auto">
        <div class="w-1/2">
            <form action="" wire:submit.prevent="addSelected">
                <h1 class="my-4 font-bold text-xl text-center uppercase">Sumar stock de los recursos y condimentos</h1>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist label="Recurso" model="recurso" inputId="recurso_id" listName="recursosList">
                            @foreach ($recursos as $id => $item)
                                <option data-value="{{ $id }}" value="{{ $item }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="recurso_id">Requerido</x-input-error>
                    </div>
                    @if ($brands)
                        <div class="w-full h-full ">
                            <x-base-select label="Atributo" wire:model="brand_id" id="brand_id">
                                <option value=""></option>
                                @foreach ($brands as $id => $brd)
                                    <option value="{{ $id }}">{{ $brd }}</option>
                                @endforeach
                            </x-base-select>

                            <x-input-error for="brand_id">Requerido</x-input-error>
                        </div>
                        <div>
                            <x-base-input type="number" label="Cant." wire:model.defer="cant" id="cant" />
                            <x-input-error for="cant">Requerido</x-input-error>
                        </div>
                    @endif
                </div>
                <div class="flex w-full my-4 justify-end">
                    <x-button class="bg-teal-600">Añadir</x-button>
                </div>

            </form>
            @if (count($selected))
                <div class="py-4 mt-4 flex flex-col">
                    <div
                        class="relative grid grid-cols-12 items-center gap-4 w-full px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg bg-blue-100 uppercase">
                        <div class="col-span-1"></div>
                        <div class="col-span-1">#</div>
                        <div class="col-span-5">Recurso</div>
                        <div class="col-span-3">Atributo</div>
                        <div class="text-right col-span-2">CANT </div>
                    </div>
                    @foreach ($selected as $id => $item)
                        <div
                            class="relative grid grid-cols-12 items-center gap-4 px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            <span class="fas fa-times text-red-400 col-span-1"
                                wire:click="removeRecurso('{{ $id }}')"></span>
                            <div class="col-span-1">{{ $id + 1 }}</div>
                            <div class="col-span-5">{{ $item['recurso'] }}</div>
                            <div class="col-span-3">{{ $item['brand'] }}</div>
                            <div class="text-right col-span-2">{{ $item['cant'] }}</div>
                        </div>
                    @endforeach
                    <div class="py-2 flex justify-end">
                        ${{ formatNumber($total) }}
                    </div>
                </div>
              
                <div class="flex w-full my-4 justify-between items-center">
                    <div>
                        <x-toggle label="Generar Gasto" id="setCost" wire:model="setCost" value="true">
                        </x-toggle>
                    </div>
                    <x-button role="button" class="bg-cyan-700" wire:click="storeSelected">Guardar</x-button>
                </div>
            @endif
        </div>
        @if (count($selected) && $setCost)
            <div class="w-1/2">
                @include('livewire.products.includes.sumproductmoney')
            </div>
        @endif
        @if (!$setCost)

            <div class="flex flex-col space-y-4">
                <div class="flex space-x-4 items-start pt-12">
                    <div class="w-full">
                        <x-base-select id="outProvider" label="Proveedor" wire:model="provider_id">
                            <option class="text-gray-300"> Elija un proveedor</option>
                            @foreach ($providers as $idProv => $prov)
                                <option value="{{ $idProv }}">{{ $prov }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="provider_id">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select id="outCountCode" label="Cuenta afectada" wire:model.defer="count_code">
                            <option class="text-gray-300"> Elija una cuenta</option>
                            @foreach ($counts as $code => $count)
                                <option value="{{ $code }}">{{ $count }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="count_code">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="text" id="outRef" label="Referencia"
                            placeholder="NCF u otro referencia" wire:model.defer="ref">
                        </x-base-input>
                        <x-input-error for="ref">Campo requerido</x-input-error>
                    </div>
                </div>
                @if ($provider_id == 1)
                    <div class="flex space-x-4 pt-4">
                        <div class="w-full">
                            <x-base-input label="Nombre del proveedor" wire:model.lazy="prov_name" id="provName">
                            </x-base-input>

                        </div>
                        <div class="w-full">
                            <x-base-input label="RNC del proveedor" wire:model.defer="prov_rnc" id="provRNC"
                                wire:keydown.enter.prevent="loadProvFromRNC">
                            </x-base-input>
                            <x-input-error for="form.type">Verifique el campo</x-input-error>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
