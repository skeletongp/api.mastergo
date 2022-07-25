<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices') }}
    @endslot

    <div class=" w-full px-4 ">
       
       <livewire:contables.report607  />
    </div>

</x-app-layout>
