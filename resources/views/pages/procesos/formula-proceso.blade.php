<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('procesos.formula', $proceso) }}
    @endslot


    <div class=" w-full relative">
        <div class=" mx-auto max-w-4xl py-2 min-h-max h-full  sm:px-6 lg:px-8">
          <div class="flex space-x-4">
             <div class="pt-8 w-full">
                @livewire('procesos.formula-proceso', ['proceso' => $proceso], key(uniqid()))
             </div>
              <div class="w-full">
                @livewire('procesos.formula-table', ['proceso' => $proceso], key(uniqid()))
              </div>

          </div>
        </div>
    </div>

</x-app-layout>
