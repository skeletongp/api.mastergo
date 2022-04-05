<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos') }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <div class="flex justify-end pb-2">
                <livewire:recursos.create-recurso />
            </div>
            <livewire:products.table-product />
        </div>
    </div>

</x-app-layout>
