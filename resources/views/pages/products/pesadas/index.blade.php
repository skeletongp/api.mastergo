<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot
    @slot('rightButton')
    <div class="flex space-x-4">
        @can('Sumar Productos')
            <div class="flex justify-end pb-2">
                <a href="{{ route('pesadas.create') }}"
                    class=" right-2 load rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                    <span>Crear</span>
                </a>
            </div>
        @endcan
       
    </div>
@endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:pesadas.pesada-table  />
        </div>
    </div>

</x-app-layout>
