<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos') }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <div class="flex justify-end pb-2">
                <a href="{{route('procesos.create')}}" 
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    <span class="fas w-6 text-center fa-plus mr-2"></span>
                    <span> Crear Proceso</span>
                </a>
            </div>
            <livewire:procesos.table-proceso />
        </div>
    </div>

</x-app-layout>
