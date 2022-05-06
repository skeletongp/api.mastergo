<div class="flex space-x-4 items-center justify-center">
    @if ($client['lastname'] !== 'Genérico ')
        <livewire:clients.edit-client :client="$client" :wire:key="uniqid()" />
    @endif
</div>
