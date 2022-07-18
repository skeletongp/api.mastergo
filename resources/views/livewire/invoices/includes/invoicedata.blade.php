<div class="w-full grid grid-cols-2 gap-x-4 items-start">
    <div class="space-y-2">
        <div class="">
            <x-base-input id="number" wire:model="number" disabled class="text-right py-1" label="Pedido Nº.">
            </x-base-input>
        </div>
        <div>
            <x-base-select status="{{auth()->user()->hasPermissionTo('Fiar Facturas')?'':'disabled'}}" id="condition" wire:model="condition" class="text-base uppercase py-0" label="Condición">
                <option value="DE CONTADO">DE CONTADO</option>
                <option value="1 A 15 DÍAS">1 A 15 DÍAS</option>
                <option value="16 A 30 DÍAS">16 A 30 DÍAS</option>
                <option value="31 A 45 DÍAS">31 A 45 DÍAS</option>
            </x-base-select>
        </div>

    </div>
    <div class="space-y-2">
        <div class="">
            <x-base-input id="vence" type="date" wire:model="vence" disabled class="text-right py-1" label="Vencimiento">
            </x-base-input>
        </div>
        <div>
            <x-base-input id="seller" type="text" wire:model="seller" disabled
                class="text-right text-base uppercase py-0" label="Vendedor">
            </x-base-input>
        </div>

    </div>
    <div class="col-span-2 py-2 ">
        <x-base-select id="type" status="{{count($details)?'disabled':''}}" wire:model="type" class="text-sm uppercase pt-1 pb-0" label="Tipo de NCF">
            @foreach (array_slice(App\Models\Invoice::TYPES, 0, 5) as $ind => $type)
                <option value="{{ $type }}">{{ $ind }}</option>
            @endforeach
        </x-base-select>
        @if (!$compAvail)
            <span class="text-red-400">Tipo de comprobante no disponible</span>
        @endif
    </div>
    <div class="space-y-2 col-span-2">
       
        <div>
            <x-base-input id="ncf" type="text" wire:model="ncf" disabled
                class="text-left text-base uppercase py-0" label="NCF">
            </x-base-input>
        </div>

    </div>
</div>
