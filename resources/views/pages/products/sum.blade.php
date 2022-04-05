<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.sum') }}
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
          
            <h1 class="uppercase font-bold text-xl text-center mb-4">Añadir productos al stock</h1>
            <livewire:products.sum-product />
        </div>
    </div>

</x-app-layout>
