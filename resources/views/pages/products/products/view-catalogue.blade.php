<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.catalogue') }}
    @endslot

    <div class="w-full max-w-6xl flex space-x-4 bg-gray-50  mx-auto ">
        <div class="w-2/5 pb-6 relative">
            <div
                class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <livewire:products.send-catalogue />
            </div>
        </div>
        <div class=" py-2 w-full mx-auto min-h-max h-full relative ">
            <iframe src="{{$url}}" width="900" height="700" type="application/pdf"> </iframe>
        </div>
    </div>

</x-app-layout>
