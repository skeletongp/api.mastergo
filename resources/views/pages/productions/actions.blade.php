<div>
    <div class="flex space-x-4">
        @if ($production['status'] != 'Completado')
            @livewire('productions.add-recurso-to-production', ['production' => $production], key($production['id']))
            @livewire('productions.get-product-from-production', ['production' => $production], key(uniqid()))
        @endif
        @livewire('productions.edit-production', ['production' => $production], key(uniqid()))
    </div>
</div>
