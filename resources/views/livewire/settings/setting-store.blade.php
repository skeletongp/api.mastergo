<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    <livewire:store.edit-store />
    @if (auth()->user()->stores->count() > 1)
        <br>
        <livewire:general.toggle-store />
    @endif
</div>
