<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.show', $product) }}
    @endslot

    <div class=" w-full ">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 py-24 mx-auto max-w-4xl relative">
                @can('Editar Productos')
                    <div class="absolute top-1 right-2">
                        <a href="{{ route('products.edit', $product) }}"
                            class=" right-0  rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                            <span class="far fa-pen-square text-xl"></span>
                            <span>Editar</span>
                        </a>
                    </div>
                @endcan
                <div class="grid grid-cols-3 gap-6 max-w-6xl w-full mx-auto">
                   <div  class="col-span-2">
                    <livewire:products.product-show :product="$product" />
                   </div>
                    <div class="flex items-center justify-center col-span-1">
                        <div class="w-48 h-48 rounded-full  bg-center bg-cover border-4 border-gray-300 bg-no-repeat"
                            style="background-image: url({{ $product->photo }})">

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

</x-app-layout>
