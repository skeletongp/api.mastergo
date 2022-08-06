<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('cotizes') }}
    @endslot

    @slot('rightButton')
        @scope('Facturas')
            @can('Crear Cotizaciones')
                <div>
                    <a href="{{ route('cotizes.create') }}"
                        class="text-gray-900 load bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                        <span class="far w-6 text-center fa-file-invoice"></span>
                        <span> Cotizar</span>
                    </a>
                </div>
            @endcan
        @endscope
    @endslot

    <div class="flex ">
        <div class="inline-block py-2 w-full max-w-5xl mx-auto min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:cotizes.cotize-table />
        </div>
    </div>

</x-app-layout>
