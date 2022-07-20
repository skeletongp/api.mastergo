<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('incomes') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 pb-12 w-max min-h-max h-full  sm:px-6 lg:px-8">
           
            <livewire:reports.income-table/>
        </div>
    </div>

</x-app-layout>
