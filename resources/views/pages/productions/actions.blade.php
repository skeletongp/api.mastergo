<div>
    <div class="flex space-x-4">
        @if ($production['status'] != 'Completado')
            @can('Añadir Recursos')
                @livewire('productions.add-recurso-to-production', ['production' => $production], key($production['id']))
            @endcan
            @can('Añadir Resultados')
                @livewire('productions.get-product-from-production', ['production' => $production], key(uniqid()))
            @endcan
        @endif
        @can('Editar Producciones')
            @livewire('productions.edit-production', ['production' => $production], key(uniqid()))
        @endcan
    </div>
</div>
