<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('catalogue') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <iframe src="{{$pdf}}" width="700" height="700" type="application/pdf"> </iframe>
        </div>
    </div>

</x-app-layout>
