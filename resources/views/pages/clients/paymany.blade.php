<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.paymany', $invoices) }}
    @endslot

    <div class="w-full p-4 bg-gray-50  ">
        <div class="max-w-6xl mx-auto">
            <livewire:clients.pay-invoices :invoices="$invoices" />
        </div>
    </div>

</x-app-layout>
