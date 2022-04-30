<div class="flex space-x-4 items-center justify-center">
    @if ($client->lastname !== "Genérico ")
        <livewire:clients.edit-client :client="$client" :wire:key="uniqid()" />
        <livewire:general.delete-model permission="Borrar Clientes" :model="$client" title="Cliente" event=""
            :wire:key="uniqid()" />
    @endif
</div>
