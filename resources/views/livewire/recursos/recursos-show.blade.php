<div class="w-full ">
    <h1 class="text-gray-900 text-3xl title-font font-medium mb-4">{{ $recurso->name }}
    </h1>
    <div class="flex mb-4 select-none">
        <div wire:click="setComponent('recursos.recurso-detail')"
            class="cursor-pointer flex-grow  border-b-2  {{ $componentName == 'recursos.recurso-detail' ? 'text-indigo-500 border-indigo-500' : 'border-gray-300' }}  py-2 text-lg px-1">
            Detalles
        </div>
       
        <div wire:click="setComponent('recursos.recurso-history')"
            class="cursor-pointer flex-grow border-b-2 {{ $componentName == 'recursos.recurso-history' ? 'text-indigo-500 border-indigo-500' : 'border-gray-300' }} py-2 text-lg px-1">
            Historial
        </div>
    </div>
    <button
        class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
        wire:loading>
        <div class="mx-auto h-40 w-40 bg-center bg-cover"
            style="background-image: url({{ asset('images/assets/loading.gif') }})">
        </div>
    </button>
    @switch($componentName)
        @case('recursos.recurso-detail')
            <livewire:recursos.recursos-detail :recurso="$recurso" :wire:key="uniqid().'det'" />
        @break

        @case('recursos.recurso-proceso')
            <livewire:recursos.recursos-proceso :recurso="$recurso" :wire:key="uniqid().'pro'" />
        @break

        @case('recursos.recurso-history')
            <livewire:recursos.recursos-history :recurso="$recurso" :wire:key="uniqid().'his'" />
        @break
    @endswitch

</div>
