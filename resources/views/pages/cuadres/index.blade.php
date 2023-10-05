<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('cuadres') }}
    @endslot

    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <iframe src="{{ $cuadre->pdf->pathLetter }}" width="700" height="700" type="application/pdf">
            </iframe>
        </div>
    </div>
    @push('js')
        <script type="text/javascript">
            $(document).ready(function() {
                if ('URLSearchParams' in window) {
                    var searchParams = new URLSearchParams(window.location.search)
                    searchParams.set("retirado", 0);
                    var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
                    history.pushState(null, '', newRelativePathQuery);
                }
            })
        </script>
    @endpush
</x-app-layout>
