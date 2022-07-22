<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('bank_show', $bank, $type) }}
    @endslot

    <div class=" w-full ">
        <div class="flex flex-col space-y-4 py-2 w-full max-w-4xl mx-auto min-h-max h-full relative sm:px-6 lg:px-8">
            @if ($type == 'debit')
                <div class="w-full">
                    <livewire:banks.bank-debit :bank="$bank" />
                </div>
            @else
                <div class="w-full">
                    <livewire:banks.bank-credit :bank="$bank" />
                </div>
            @endif

               
        </div>
    </div>

</x-app-layout>
