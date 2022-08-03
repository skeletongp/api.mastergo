<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('general-daily') }}
    @endslot
    @slot('rightButton')
        <div class="flex space-x-4 items-center">
            <div>
                @livewire('transactions.create-transaction', key(uniqid()))
            </div>
            <div>
                @can('Crear Cuentas')
                    @livewire(
                        'contables.create-count',
                        [
                            'model' => null,
                            'chooseModel' => true,
                            'codes' => App\Models\CountMain::get()->pluck('code')->toArray(),
                        ],
                        key(uniqid()),
                    )
                @endcan
            </div>
        </div>
    @endslot
    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:contables.historial-daily />
        </div>
    </div>

</x-app-layout>
