<div class="flex space-x-4 items-center justify-center">
    @if ($provider_id != 1)
        <livewire:providers.edit-provider :provider_id="$provider_id" :wire:key="uniqid()" />
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endif
</div>
