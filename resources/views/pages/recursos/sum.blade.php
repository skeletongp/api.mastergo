<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('recursos.sum') }}
    @endslot

    <div class=" w-full relative">
        @livewire('recursos.sum-recurso', key(uniqid())) 
    </div>

</x-app-layout>
