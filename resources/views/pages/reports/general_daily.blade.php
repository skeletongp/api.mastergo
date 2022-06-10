<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot
    @slot('rightButton')
       @livewire('transactions.create-transaction', key(uniqid()))
    @endslot
    <div class=" w-full ">
        {{auth()->user()->place->id}}
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:reports.general-daily-table />
        </div>
    </div>

</x-app-layout>
