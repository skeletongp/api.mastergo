<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 " >
    <button class="text-xl font-bold uppercase hover:text-blue-500 hover:underline cursor-pointer"
    wire:click="changeView">
        {{$isUnit ?'Mostrar impuestos':'Mostrar unidades'}}
    </button>
    @switch($isUnit)
        @case(true)
            <div>
                <livewire:settings.setting-unit />
            </div>
        @break
        @case(false)
            <div>
                <livewire:settings.setting-tax />
            </div>
        @break
    @endswitch



</div>
