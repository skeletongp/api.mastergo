
<x-app-layout>
  {{--   @slot('bread')
        {{ Breadcrumbs::render('procesos.show', $proceso) }}
    @endslot --}}

    <div class=" w-full relative">
        <div class=" mx-auto py-2 min-h-max h-full  sm:px-6 lg:px-8">
            @livewire('productions.show-production', ['production' => $production], key(uniqid()))
        </div>
    </div>

</x-app-layout>
