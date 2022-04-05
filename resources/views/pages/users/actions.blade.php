<div>
    <div class="flex flex-row justify-around items-center space-x-2">
        @can('Editar Usuarios')
            <div>
                <livewire:users.edit-user :user="$user" :wire:key="uniqid()" />
            </div>
        @endcan

        @if (!$user->hasRole('Administrador') ||
    auth()->user()->hasRole('Super Admin'))
            @can('Asignar Permisos')
                <div>
                    <livewire:users.assign-permission :user="$user" :wire:key="uniqid().'id'" />
                </div>
            @endcan
            @can('Borrar Usuarios')
                <livewire:general.delete-model :model="$user" event="refreshLivewireDatatable" title="Usuario"
                    permission="Borrar Usuarios" :wire:key="uniqid().'id2'" />
            @endcan
        @endif
    </div>

</div>
