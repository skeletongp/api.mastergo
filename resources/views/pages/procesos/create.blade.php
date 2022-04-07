<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos.create') }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:procesos.create-proceso />
        </div>
    </div>

</x-app-layout>
