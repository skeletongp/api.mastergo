<div>
    <x-modal  title="Editar detalle">
        <x-slot name="button">
            <span class="far fa-pen-alt"></span>
        </x-slot>
            @include('includes.authorize')
        <div>
            <div class="space-y-4 text-left">
                <div class="flex space-x-2">
                    <div class="w-1/5">
                        <x-base-input type="number" disabled id="Dcode{{ $detail->id }}" inputClass="" label="Cód."
                            wire:model.lazy="product.code">

                        </x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-select disabled id="Dname{{ $detail->id }}" class=" w-full " label="Producto"
                            wire:model="product.code">
                            @foreach ($products as $id => $prod)
                                <option value="{{ $id }}">{{ $prod }}</option>
                            @endforeach
                        </x-base-select>
                    </div>
                </div>
                @if ($units)
                    <div class="flex space-x-2">
                        <div class="w-full">
                            <x-base-input type="number" maz="999" id="Dcant{{ $detail->id }}" inputClass="" label="Cant."
                                wire:model.lazy="detail.cant">

                            </x-base-input>
                        </div>
                        <div class="w-full">
                            <x-base-input type="number" id="Dprice{{ $detail->id }}" inputClass="" label="Precio"
                                wire:model.lazy="detail.price">

                            </x-base-input>
                        </div>
                        <div class="w-full">
                            <x-base-select id="Dunit{{ $detail->id }}" class=" w-full " label="Unidad"
                                wire:model="unit.pivot.id">
                                @foreach ($units as $id => $und)
                                    <option value="{{ $id }}">{{ $und }}</option>
                                @endforeach
                            </x-base-select>
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex justify-end my-2">
                <x-button wire:loading.disabled wire:click.prevent="tryUpdateDetail">
                    Actualizar
                </x-button>
            </div>
        </div>

    </x-modal>

</div>