<div class="relative">
    <x-modal :fitVerticalContainer="false" minHeight='min-h-[50vh]'>
        <x-slot name="button">
            <span class="fas fa-plus text-xl mr-2"></span>
            <span>Añadir Medida</span>
        </x-slot>
        <x-slot name="title">
            <h1 class="text-center pb-4 uppercase font-bold text-xl">Nuevos Precios</h1>
        </x-slot>
        @can('Cambiar Precios')
            <form action="" wire:submit.prevent="addPrice ">
                <div class="space-y-4">
                    @foreach ($units as $id => $unit)
                        <div class="flex space-x-4 items-end">
                            <x-input class="font-bold uppercase" readonly value="{{ $unit }}" label="Medida"
                                id="med{{ $id }}"></x-input>
                            <div class="max-w-xs">
                                <x-input label="Costo" type="number" id="cost{{ $id }}"
                                    x-value="{{ $id }}" wire:model.defer="form.{{ $id }}.cost">
                                </x-input>
                                <x-input-error for="form.{{ $id }}.cost"></x-input-error>
                            </div>
                            <div class="max-w-xs">
                                <x-input label="Precio" type="number" id="price{{ $id }}"
                                    wire:model.defer="form.{{ $id }}.price"></x-input>
                                <x-input-error for="form.{{ $id }}.price"></x-input-error>
                            </div>
                            <div class="max-w-xs">
                                <x-button>
                                    <span class="fas fa-save"></span>
                                </x-button>
                            </div>
                        </div>
                    @endforeach
                    <x-input-error for="form">Debe ingresar los datos</x-input-error>
                </div>
            </form>
        @endcan
    </x-modal>
</div>
