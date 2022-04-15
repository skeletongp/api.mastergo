<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices.orders') }}
    @endslot

    <div class=" w-full ">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
         <div class="max-w-3xl">
            <livewire:invoices.order-view />
         </div>
        </div>
    </div>

</x-app-layout>
