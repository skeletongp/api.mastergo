<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.paymany', $invoices) }}
    @endslot

    <div class="w-full p-4 bg-gray-50  ">
        <div class="max-w-6xl mx-auto">
            <livewire:clients.print-many :invoice_ids="$invoices" />
        </div>
    </div>

</x-app-layout>
