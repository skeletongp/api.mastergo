<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    @can('Crear Sucursales')
        <div class="flex flex-col mx-auto w-max space-y-2 relative">
            <livewire:settings.places.create-place />
            <livewire:settings.places.place-index />
        </div>
        
    @endcan
</div>