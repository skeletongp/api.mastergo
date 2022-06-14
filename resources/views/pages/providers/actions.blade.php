<div class="flex space-x-4 items-center justify-center">
    @if ($provider['id'] !== auth()->user()->store->prov_generic->id)
        <livewire:providers.edit-provider :provider="$provider" :wire:key="uniqid()" />
        @else
        <span class="fas fa-ban text-red-400" ></span>
    @endif
</div>
