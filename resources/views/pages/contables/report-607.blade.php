<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices') }}
    @endslot

    <div class=" w-full ">
        <div class="w-full flex  items-start">
            <div class=" mx-auto relative w-full">
                @if ($url)
                    <iframe src="{{ $url }}#view=FitH" width="100%" height="700" type="application/pdf">
                    </iframe>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
