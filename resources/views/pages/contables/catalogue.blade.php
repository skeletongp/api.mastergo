<x-app-layout>

    @slot('bread')
        {{ Breadcrumbs::render('catalogue') }}
    @endslot
    @slot('rightButton')
        <div class="flex space-x-4 items-cen">
            <a href="{{ route('contables.view_catalogue') }}"
                class=" right-2  rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                <span class="fas fa-file-pdf text-xl"></span>
                <span>Ver PDF</span>
            </a>
            @can('Crear Productos')
                @livewire(
                    'contables.create-count',
                    [
                        'model' => null,
                        'chooseModel'=>true,
                        'codes' => App\Models\CountMain::get()->pluck('code')->toArray(),
                    ],
                    key(uniqid()),
                )
            @endcan
        </div>
    @endslot
    {{--  --}}
    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            @livewire('contables.catalogue', key(uniqid()))
        </div>

    </div>
    </div>
</x-app-layout>
