<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
   
    <div class="grid grid-cols-3 gap-3 px-4">
        <livewire:dashboard.stat-card />
        <livewire:dashboard.stat-week />
        <div class="col-span-2">
            <livewire:dashboard.last-sales />
        </div>
        <div class="col-span-1">
            <livewire:dashboard.last-products />
        </div>
    </div>

</x-app-layout>
