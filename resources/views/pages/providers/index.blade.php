<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('providers') }}
    @endslot
    @slot('rightButton')
        @livewire('providers.create-provider',  key(uniqid()))
    @endslot
    <div class=" w-full mx-auto ">

        <div class="mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
                <livewire:providers.provider-table />
        </div>
    </div>

</x-app-layout>
