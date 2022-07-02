<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('cheques') }}
    @endslot
    @slot('rightButton')
        @livewire('cheques.create-cheque', key(uniqid()))
    @endslot
    <div class="w-full bg-gray-50  mx-auto ">
        <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
            <livewire:cheques.cheque-list />
        </div>
    </div>
</x-app-layout>