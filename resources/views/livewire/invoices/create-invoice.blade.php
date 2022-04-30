<div class="w-full">

    <div class="grid grid-cols-12 gap-2 ">
        {{-- Área de productos --}}
        <div class="col-span-5 relative">
           <div class="flex space-x-4 max-w-sm p-3">
           <x-input label="Buscar por Código o Nombre" type="search" id="searchImput" onkeypress="catchSearchEnter(event)"></x-input>
           </div>
            <div class="  grid grid-cols-3 gap-10 p-6 shadow-lg">
                @forelse ($products as $prod)
                    <div class="relative min-h-[9rem] {{ optional($producto)->id == $prod->id ? 'shadow-inner shadow-lg shadow-cyan-300 p-2' : '' }} mb-4 max-h-[9rem] select-none cursor-pointer"
                        wire:click="setProduct({{ $prod->id }})">
                        <div class="w-full h-full bg-contain bg-no-repeat bg-center"
                            style="background-image: url({{ $prod->photo }})">

                        </div>
                        <h1
                            class=" py-2  overflow-hidden overflow-ellipsis whitespace-nowrap uppercase font-bold mx-auto ">
                            {{ $prod->name }}</h1>
                    </div>
                @empty
                    <h1>No se halló ningún resultado</h1>
                @endforelse
            </div>
            @if ($products->count())
                {{ $products->links() }}
            @endif
        </div>

        {{-- Área de montos --}}
        <div class="col-span-3 flex flex-col justify-start h-full shadow-lg">
            <x-keyboard></x-keyboard>

            <div class="w-full ">
                <ul class="p-3">
                    @foreach ($errors->toArray() as $ind => $error)
                        <x-input-error for="{{ $ind }}"></x-input-error>
                    @endforeach
                </ul>
                @if ($producto)
                    <h1 class="text-2xl font-bold uppercase p-4 overflow-hidden overflow-ellipsis whitespace-nowrap ">
                        {{ $producto->name }}</h1>
                    <div class=" space-x-2 h-full w-max px-4 text-lg font-bold uppercase">
                        <h1 class="text-left font-bold uppercase mb-4"> MEDIDA <small>máx. {{ $maxCant }}</small>
                        </h1>
                        <div class=" grid grid-cols-3 gap-2">
                            @foreach ($producto->units as $unit)
                                <div class="auto-cols-max">
                                    <x-radio label="{{ $unit->name }}" id="unit{{ $unit->pivot->id }}" name="unit"
                                        value="{{ $unit->pivot->id }}" wire:model="form.unit_id"></x-radio>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-4 py-8">
                            <x-input onkeydown="kdwnPrice(event)" label="Precio" onfocus="setFocused(true)" onblur="setFocused(false)" type="number"
                                wire:model.defer="form.price" id="formInvPrice">
                            </x-input>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Area de facturados --}}
        @include('livewire.invoices.includes.factured')
    </div>
    {{-- Scritp de la vista --}}
    @include('livewire.invoices.includes.invoice-js')
</div>
