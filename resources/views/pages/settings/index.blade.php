<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('settings') }}
    @endslot

    <div class=" w-full ">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
         <livewire:settings.setting-index />
        </div>
    </div>

</x-app-layout>
