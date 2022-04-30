<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
    <div class="px-4 my-4 ">
        <livewire:general.toggle-place />
    </div>
    <div class="grid grid-cols-4 gap-6 px-4">
    <livewire:dashboard.stat-card 
        icon="fas fa-dollar-sign" title="Ingresos" value="500"
    />

    </div>

</x-app-layout>
