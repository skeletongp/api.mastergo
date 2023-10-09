<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('cotizes') }}
    @endslot

    <div class=" w-full px-4 ">
        <div class=" mx-auto max-w-2xl relative w-full">
            @if ($url)
                <iframe src="{{ $url }}" width="100%" height="700" type="application/pdf">
                </iframe>
            @endif
        </div>
    </div>

</x-app-layout>
