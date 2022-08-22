<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('outcomes') }}
    @endslot

    <div class=" w-full px-4 ">
       
       <livewire:contables.report606  />
    </div>

</x-app-layout>
