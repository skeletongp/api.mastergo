<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos') }}
    @endslot
    @slot('rightButton')
        <div class="flex justify-end space-x-4 items-center pb-2">
            @can('Crear Procesos')
                <livewire:procesos.create-proceso />
            @endcan
           
        </div>
    @endslot

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
