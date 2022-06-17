<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('general-mayor') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
          <livewire:contables.general-mayor-table />
        </div>
    </div>

</x-app-layout>
