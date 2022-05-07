<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <h1 class="text-center font-bold uppercase text-xl mb-4">Diario General</h1>
           <livewire:reports.general-daily-table />
        </div>
    </div>

</x-app-layout>