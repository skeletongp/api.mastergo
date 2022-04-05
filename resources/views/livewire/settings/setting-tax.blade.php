<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    <h1 class="font-bold text-xl uppercase text-center mb-4">Impuestos</h1>
    @can('Crear Unidades')
        <livewire:settings.taxes.create-tax />
        <hr>
        <br>
        <div class="py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:settings.taxes.tax-table />
        </div>
    @endcan
</div>
