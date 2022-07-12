<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recurrents') }}
    @endslot
    @slot('rightButton')
    <livewire:recurrents.create-recurrent />
    @endslot
    <div class=" w-full mx-auto ">
        <div class="mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:recurrents.recurrent-table />
        </div>
    </div>
</x-app-layout>