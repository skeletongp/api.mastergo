<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('cuadres.show', $cuadre) }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <iframe src="{{$cuadre->pdf->pathLetter}}" width="700" height="700" type="application/pdf"> </iframe>
        </div>
    </div>

</x-app-layout>
