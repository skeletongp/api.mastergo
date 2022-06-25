<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos.show', $proceso) }}
    @endslot
    @slot('rightButton')
    <div>
        <a href="{{ route('procesos.formula', $proceso) }}"
            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <span class="fas w-6 text-center fa-flask"></span>
            <span> Fórmula</span>
        </a>
    </div>
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 min-h-max h-full  sm:px-6 lg:px-8">
            <livewire:procesos.proceso-show :proceso="$proceso" />
        </div>
    </div>

</x-app-layout>
