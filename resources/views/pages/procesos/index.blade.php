<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos') }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <div class="flex justify-end pb-2">
              <livewire:procesos.create-proceso />
            </div>
            <livewire:procesos.table-proceso />
        </div>
    </div>

</x-app-layout>
