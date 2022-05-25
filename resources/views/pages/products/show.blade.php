<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.show', $product) }}
    @endslot

    <div class=" w-full ">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 py-24 mx-auto max-w-4xl relative">
               <div class="absolute top-1 right-2">
                <a href="{{route('products.edit', $product)}}"class=" right-0  rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                    <span class="far fa-pen-square text-xl"></span>
                    <span>Editar</span>
                </a>
               </div>
                <div class="grid grid-cols-2 gap-6 max-w-6x w-full mx-auto">
                    <livewire:products.product-show :product="$product" />
                    <div class="flex items-center justify-center">
                        <div class="w-full h-full  bg-center bg-contain bg-no-repeat"
                            style="background-image: url({{ $product->photo }})">

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    
</x-app-layout>
