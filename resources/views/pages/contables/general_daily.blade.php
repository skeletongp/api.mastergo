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
            <div>
                <a href="{{ route('contables.historial_daily') }}"
                    class="text-gray-900 load bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    <span class="far w-6 text-center fa-clock"></span>
                    <span> Historial</span>
                </a>
            </div>
        </div>
    @endslot
    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:contables.general-daily-table />
        </div>
    </div>

</x-app-layout>
