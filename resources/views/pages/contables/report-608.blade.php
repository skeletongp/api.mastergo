<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices') }}
    @endslot
    @slot('rightButton')
        <ul class="flex space-x-4">
            <x-side-link routeName='contables.report_606' icon='fas w-10 text-center fa-file-download fa-lg' text='Reporte 606'
                activeRoute="contables.*" :scope="'Impuestos'" />
            <x-side-link routeName='contables.report_607' icon='fas w-10 text-center fa-file-upload fa-lg' text='Reporte 607'
                activeRoute="contables.*" :scope="'Impuestos'" />
            <x-side-link routeName='contables.report_608' icon='fas w-10 text-center fa-file-upload fa-lg' text='Reporte 608'
                activeRoute="contables.*" :scope="'Impuestos'" />
        </ul>
    @endslot
    <div class=" w-full px-4 ">

        <livewire:contables.report608 />
    </div>

</x-app-layout>
