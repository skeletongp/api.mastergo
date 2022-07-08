<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('productions', $production) }}
    @endslot

    <div class=" w-full relative">
        <div class=" mx-auto py-2 min-h-max h-full  sm:px-6 lg:px-8">
            @if ($production->status == 'Completado')
                @livewire('productions.production-detail', ['production' => $production], key(uniqid()))
            @else
                @livewire('productions.show-production', ['production' => $production], key(uniqid()))
            @endif
        </div>
    </div>

</x-app-layout>
