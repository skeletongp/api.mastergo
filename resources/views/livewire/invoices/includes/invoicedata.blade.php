<div class="max-w-sm grid grid-cols-2 gap-x-4">
    <div class="space-y-2">
        <div class="pt-1">
            <x-base-input id="number" wire:model="number" disabled class="text-right py-0" label="Pedido Nº.">
            </x-base-input>
        </div>
        <div>
            <x-base-select id="condition" wire:model="condition"  class="text-base uppercase py-0" label="Condición">
                <option value="DE CONTADO">DE CONTADO</option>
                <option value="1 A 15 DÍAS">1 A 15 DÍAS</option>
                <option value="16 A 30 DÍAS">16 A 30 DÍAS</option>
                <option value="31 A 45 DÍAS">31 A 45 DÍAS</option>
            </x-base-select>
        </div>
      
    </div>
    <div class="space-y-2">
        <div class="pt-1">
            <x-base-input id="vence" type="date" wire:model="vence"  class="text-right py-0" label="Vencimiento">
            </x-base-input>
        </div>
        <div>
            <x-base-input id="seller" type="text" wire:model="seller" disabled class="text-right text-base uppercase py-0" label="Vendedor">
            </x-base-input>
        </div>
       
    </div>
    <div class="col-span-2">
        <x-base-select id="type" wire:model="type"  class="text-base uppercase py-0" label="Tipo de NCF">
            @foreach (App\Models\Invoice::TYPES as $ind => $type)
                    <option value="{{ $type }}">{{ $ind }}</option>
                @endforeach
        </x-base-select>
    </div>
</div>
