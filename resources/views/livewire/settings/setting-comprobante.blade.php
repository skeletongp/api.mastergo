<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    <h1 class="font-bold text-xl uppercase text-center mb-4">Comprobantes Fiscales</h1>
    @can('Crear Comprobantes')
        <livewire:settings.comprobantes.create-comprobante />
        <hr>
        <br>
        <livewire:settings.comprobantes.comprobante-index />

    @endcan
</div>
