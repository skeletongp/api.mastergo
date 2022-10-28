<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('categories.show', $category) }}
    @endslot
    @slot('rightButton')
        <div class="flex space-x-4">
            @can('Crear Categorias')
                <div class="flex justify-end pb-2">
                   <livewire:categories.create-category />
                </div>
                <div class="flex justify-end pb-2">
                    <livewire:categories.add-category-product :category_id="$category->id" />
                 </div>
            @endcan
           
        </div>
    @endslot

    <div class=" w-full ">
        <div class=" mx-auto py-2 max-w-4xl   h-full  sm:px-6 lg:px-8">
            <livewire:categories.category-product-table  :category_id="$category->id" :category_name="$category->name"/>
        </div>
    </div>

</x-app-layout>
