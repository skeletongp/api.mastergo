<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices.show', $invoice) }}
    @endslot
    <div class=" w-full ">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:invoices.invoice-show :invoice="$invoice" />
        </div>
    </div>
</x-app-layout>
