<div class="flex space-x-4 items-center justify-center">
    @if ($client['id'] !== auth()->user()->store->generic->id)
        <livewire:clients.edit-client :client="$client" :wire:key="uniqid()" />
        @else
        <span class="fas fa-ban text-red-400" ></span>
    @endif
</div>
