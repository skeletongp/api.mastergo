<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            @livewire('clients.client-invoice', ['client_id' => $client_id], key(uniqid()))
        </div>
    </div>

</x-app-layout>
