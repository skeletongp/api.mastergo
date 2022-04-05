<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <livewire:clients.table-client />
        </div>
    </div>

</x-app-layout>
