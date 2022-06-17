<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products') }}
    @endslot
    @slot('rightButton')
        <div class="flex space-x-4">
            @can('Sumar Productos')
                <div class="flex justify-end pb-2">
                    <a href="{{ route('products.sum') }}"
                        class=" right-2  rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                        <span class="fas fa-plus text-xl"></span>
                        <span>Sumar</span>
                    </a>
                </div>
            @endcan
            @can('Crear Productos')
                @livewire('contables.create-count', ['model' => 'App\Models\Product', 'codes' => ['104', '500']], key(uniqid()))
            @endcan
        </div>
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:products.table-product :all="true" />
        </div>
    </div>

</x-app-layout>
