<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoice-historial') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 pb-12 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:reports.invoice-historial/>
        </div>
    </div>

</x-app-layout>
