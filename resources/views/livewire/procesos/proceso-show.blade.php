<div class="w-full flex  items-start">
    <div class=" relative select-none" style="width: 34rem; max-width: 34rem">
        @if (1)
            <div
                class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <div aria-current="true"
                    class="block w-full px-4 py-2 pb-3 text-gray-800 bg-gray-100  rounded-tl-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-xl uppercase text-center font-bold">
                    Procesos
                </div>
                @foreach ($procesos as $proc)
                    <div wire:click="setProcess('{{ $proc->id }}')" id="divproceso"
                        class="flex load flex-col relative overflow-hidden w-full px-4 mb-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $proceso->id == $proc->id ? 'text-blue-700' : '' }}">

                        {{-- Indicador de progreso --}}
                        <div class="absolute top-0 bottom-0 left-0 bg-green-500 opacity-30 my-2 z-10"
                            style="right: {{ 100 - Universal::formatNumber($proc->eficiency) }}%">

                        </div>
                        <span class=" z-20 text-base w-full overflow-hidden overflow-ellipsis whitespace-nowrap pr-4">
                            {{ $proc->name }}
                        </span>
                        <span class=" z-20 text-base">
                            {{ 'Progreso =>' }}
                        </span>
                        <span class=" z-20 text-base">
                            {{ $proc->status }}
                        </span>
                        <div class=" z-20 absolute right-2 top-8 flex flex-col  text-right">
                            <span class=" text-2xl font-bold">
                                {{ Universal::formatNumber($proc->eficiency) }}%
                            </span>
                            <span class=" z-20 text-base">
                                {{ date_format(date_create($proc->start_at), 'd-m-Y') }}
                            </span>
                        </div>
                    </div>
                    <hr>
                @endforeach
                <div class="my-2">
                    {{ $procesos->onEachSide(1)->links('vendor.livewire.tailwind') }}
                </div>

            </div>
        @endif
    </div>
    <div class="w-full h-full  pl-0" x-data="{ open: true }">
        @if ($procesos->count() && $proceso)
            <div class="mx-auto ">

                <div class="flex justify-end items-center py-2  space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                    <h1 class="text-center font-bold uppercase text-xl w-full">Productos Esperados</h1>
                    <x-button>
                        <span class="fas fa-print" data-tooltip-target="tooltip-print"
                            data-tooltip-style="light"></span>
                        <x-tooltip id="tooltip-print">Impresión Térmica</x-tooltip>
                    </x-button>
                    <x-dropdown wClass="w-48" class="order-2">
                        <x-slot name="trigger">
                            <span class="far fa-ellipsis-h-alt">
                        </x-slot>
                        <x-slot name="content">
                            <x-button class="px-3 py-1.5 flex space-x-2 items-center">
                                <span class="fas fa-plus"></span>
                                <span>Prueba</span>
                            </x-button>
                        </x-slot>
                    </x-dropdown>
                </div>
                <div class="w-full relative px-12 py-8 space-y-8" style=" height:215.4mm">
                    @foreach ($proceso->products()->with('procunits')->get()
    as $product)
                        <div class="flex space-x-4">
                            <div class="w-full">
                                <x-input class="w-full" id="pv.name" label="Producto" readonly
                                    value="{{ $product->name }}"></x-input>
                            </div>
                            <div>
                                <x-input id="pv.unit" label="Medida" readonly
                                    value="{{ $product->procUnit($proceso->id)->name }}"></x-input>
                            </div>
                            <div>
                                <x-input id="pv.due" label="Esperado" readonly value="{{ $product->pivot->due }}">
                                </x-input>
                            </div>
                            <div>
                                <x-input id="pv.obtained" label="Obtenido" readonly
                                    value="{{ $product->pivot->obtained }}"></x-input>
                            </div>
                            <form action="" class="flex space-x-4 w-full"
                                wire:submit.prevent="setObtained({{ $product->pivot->id }})">
                                <div class="max-w-[5rem]">
                                    <x-input id="pv.add{{ $product->id . rand(0, 9) }}" type="number" required min="1"
                                        class="text-blue-400" label="Añadir"
                                        wire:model.defer="productos.{{ $product->id }}"></x-input>
                                </div>
                                <x-button>
                                    <span class="far fa-sync"></span>
                                </x-button>
                            </form>
                        </div>
                    @endforeach


                </div>
            </div>
        @else
            <div class="h-full w-full flex flex-col items-center justify-center">
                <h1 class="text-3xl leading-8 max-w-sm text-center uppercase font-bold">Seleccione una factura para
                    obtener una vista previa</h1>
            </div>
        @endif

    </div>
</div>
