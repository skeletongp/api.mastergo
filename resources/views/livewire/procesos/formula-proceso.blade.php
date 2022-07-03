<div class="w-full">
    <form action="" wire:submit.prevent="createFormula">
        <div class="py-4">
            <h1 class="font-bold uppercase">
                Añadir a la fórmula
            </h1>
        </div>
        <div class="space-y-4">
            <span>Los montos son por cada unidad a producir</span>
            <div class="flex space-x-4">
                <div class="w-full">
                    <x-datalist :inputId="'fRecu'" label="Recurso/Condimento" model="form.recurso" listName="fRecurso">
                        @foreach ($recursos as $ide => $recurso)
                            <option data-value="{{ $ide }}" value="{{ $recurso }}"></option>
                        @endforeach
                    </x-datalist>
                   
                    <x-input-error for="form.recurso"> </x-input-error>
                </div>
                <div>
                    <x-base-input wire:model.defer="form.cant" label="Cant." type="number" id="form.f.cant"></x-base-input>
                    <x-input-error for="form.cant">Requerido </x-input-error>
                </div>
            </div>
            <div class="flex space-x-4">
                <div class="w-1/2">
                    <x-base-select label="Medida" wire:model="form.unit_id" id="form.f.unit_id">
                        <option value=""></option>
                        @foreach ($units as $inde => $unit)
                            <option value="{{ $inde }}">{{ $unit }}</option>
                        @endforeach
                    </x-base-select>
                    <x-input-error for="form.unit_id">Campo requerido </x-input-error>
                </div>
                @if ($brands)
                <div class="w-1/2">
                    <x-base-select label="Atributo" wire:model="form.brand_id" id="form.f.brand_id">
                        <option value=""></option>
                        @foreach ($brands as $ind => $brand)
                            <option value="{{ $ind }}">{{ $brand }}</option>
                        @endforeach
                    </x-base-select>
                    <x-input-error for="form.brand_id">Campo requerido </x-input-error>
                </div>
                @endif
            </div>
            <div class="flex justify-end">
                <x-button>Añadir</x-button>
            </div>
        </div>
    </form>
</div>
