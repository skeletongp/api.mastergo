<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos.sum') }}
    @endslot

    <div class=" w-full max-w-6xl mx-auto relative flex space-x-4">
        <div class="w-full">
            @livewire('recursos.sum-recurso', key(uniqid())) 
            
        </div>
      
    </div>

</x-app-layout>
