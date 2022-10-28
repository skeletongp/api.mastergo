<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('home') }}
    @endslot
   
    <div class="lg:grid  lg:grid-cols-3 gap-3 px-4 space-y-4 lg:space-y-0">
        <div class="w-full">
            <livewire:dashboard.stat-card />
        </div>
        <div class="w-full lg:col-span-2">
            <livewire:dashboard.stat-week />
        </div>
        <div class="lg:col-span-2">
            <livewire:dashboard.last-sales />
        </div>
        <div class="lg:col-span-1">
            <livewire:dashboard.last-products />
        </div>
    </div>

</x-app-layout>
