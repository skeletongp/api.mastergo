<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.create') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
           <livewire:products.create-product />
        </div>
    </div>

</x-app-layout>
