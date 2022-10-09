<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('categories') }}
    @endslot
    @slot('rightButton')
        <div class="flex space-x-4">
            @can('Crear Categorias')
                <div class="flex justify-end pb-2">
                   <livewire:categories.create-category />
                </div>
            @endcan
           
        </div>
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 max-w-4xl w-full  min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:categories.category-table  />
        </div>
    </div>

</x-app-layout>
