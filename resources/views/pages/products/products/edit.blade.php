<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.edit', $product) }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
           <livewire:products.edit-product :product="$product" />
        </div>
    </div>

</x-app-layout>
