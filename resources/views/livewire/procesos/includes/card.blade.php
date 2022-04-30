<div class="">
    <div class="flex flex-col space-y-4 shadow-xl px-2 py-4">
        <div class="w-full flex space-x-2">
            <div class="w-full">
                <x-dinput label="" placeholder="Nombre del proceso" wire:model="form.name" class="uppercase"
                    disabled></x-dinput>
            </div>
            <x-dinput label="" placeholder="Fecha de inicio" wire:model="form.start_at" class="uppercase" disabled>
            </x-dinput>
        </div>
        <hr>
        <div class="flex justify-between space-x-4">
            <h1 class="font-bold  text-lg whitespace-nowrap w-max mr-4 ">Recursos requeridos:</h1>
            <h1 class=" text-lg overflow-hidden overflow-ellipsis whitespace-nowrap">
                {{ !$fRecursos? 'Seleccione un recurso': count($fRecursos) . (count($fRecursos) < 2 ? ' Recurso' : ' Recursos') }}
            </h1>
        </div>
        <div class="flex flex-col space-y-4 ">
            @if ($fProducts)
                @foreach ($fProducts as $product)
                    <div class="flex justify-between">
                        <span>
                            {{ $product['due'] }} <small>{{ $product['unitname'] }}</small>
                        </span>
                        <span>
                            {{ $product['name'] }}
                        </span>
                    </div>
                @endforeach
            @else
                <div class="flex justify-between space-x-4">
                    <h1 class="font-bold  text-lg whitespace-nowrap w-max mr-4 ">Productos esperados:</h1>
                    <h1 class=" text-lg overflow-hidden overflow-ellipsis whitespace-nowrap">
                        No ha seleccionado ningún producto
                    </h1>
                </div>
            @endif
        </div>
    </div>

</div>
