<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos.show', $recurso) }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <div class="flex justify-end pb-2">
                <livewire:recursos.create-recurso />
            </div>
            <div class="grid grid-cols-2 gap-6 max-w-5xl mx-auto">
                <livewire:recursos.recursos-show :recurso="$recurso" />
                <div class="flex items-center justify-center">
                    <div class="w-full h-full  bg-center bg-contain bg-no-repeat"
                        style="background-image: url({{ auth()->user()->store->logo }})">

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
