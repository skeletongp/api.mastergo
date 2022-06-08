<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <div class="flex justify-end space-x-4 pb-2">
                <a id="addresource" type="button" href="{{route('recursos.sum')}}"
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Sumar
                </a>
                <livewire:recursos.create-recurso />
            </div>
            <livewire:recursos.table-recurso />
        </div>
    </div>

</x-app-layout>
