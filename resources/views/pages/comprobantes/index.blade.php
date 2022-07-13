<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('comprobantes') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 px-4  mx-auto min-h-max h-full relative ">
         @livewire('comprobantes.comprobantes-table', key(uniqid()))
        </div>
    </div>

</x-app-layout>
