<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    <h1 class="font-bold text-xl uppercase text-center mb-4">Unidades y medidas</h1>
    @can('Crear Unidades')
        <livewire:settings.units.create-unit />
        <hr>
        <br>
        <div class=" py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:settings.units.unit-table />
        </div>
    @endcan

   

</div>
