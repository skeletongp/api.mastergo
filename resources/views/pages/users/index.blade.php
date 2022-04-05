<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('users') }}
    @endslot

    <div class=" w-full mx-auto ">

        <div class="mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:users.table-user />
        </div>
    </div>

</x-app-layout>
