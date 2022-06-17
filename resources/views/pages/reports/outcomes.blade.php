<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('outcomes') }}
    @endslot
    @slot('rightButton')
       @livewire('reports.create-outcome', key(uniqid()))
    @endslot
    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:reports.outcome-table />
        </div>
    </div>

</x-app-layout>
