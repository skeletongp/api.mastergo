<div class="flex space-x-4 items-center justify-center">
    @if ($client['id'] != 1)
        <div class="flex space-x-4 items-center">
            @can('Editar Clientes')
                <div>
                    <livewire:clients.edit-client :client="$client" :wire:key="uniqid()" />
                </div>
            @else
                <span class="fas fa-ban text-red-400"></span>
            @endcan
            @can('Borrar Clientes')
                <div>
                    @livewire('general.delete-model', ['model_id' => $client['id'], 'msg' => '¿Desea eliminar este cliente?', 'permission' => 'Borrar Clientes', 'class' => 'App\Models\Client'], key(uniqid()))
                    </span>
                </div>
            @else
                <span class="fas fa-ban text-red-400"></span>
            @endcan
        </div>
    @else
        <span class="fas fa-ban text-red-400"></span>
        <span class="fas fa-ban text-red-400"></span>
    @endif

</div>
