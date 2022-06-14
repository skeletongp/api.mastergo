<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos') }}
    @endslot
    @can('Crear Procesos')
        @slot('rightButton')
            <div class="flex justify-end pb-2">
                <livewire:procesos.create-proceso />
            </div>
        @endslot
    @endcan

    @can('Ver Procesos')
    <div class=" w-full relative">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:procesos.table-proceso />
        </div>
    </div>
    @else
    <div class="w-full p-8">
        <h1 class="font-bold text-lg text-center p-8">NO TIENES PERMISOS PARA ESTA VISTA</h1>
    </div>
    @endcan

</x-app-layout>
