<div x-data="show: true" x-init x-cloak>
    @php
        if (in_array('Administrador', array_column($user['roles'], 'name'))) {
            $isAdmin = true;
        } else {
            $isAdmin = false;
        }
        
    @endphp
    <div class="flex flex-row justify-start items-center space-x-2">
        @can('Editar Usuarios')
            <div>
                <livewire:users.edit-user :roles="$roles" :user="$user" :wire:key="uniqid()" />
            </div>
        @endcan

        @if (!$isAdmin)
            @can('Asignar Permisos')
                <div>
                   <a href="{{route('users.setPermissions', $user)}}">
                    <span class="far fa-shield-check fa-xl text-yellow-600" data-tooltip-target="assignPermission{{$user['id']}}"
                    data-tooltip-style="light"></span>
                    <x-tooltip id="assignPermission{{$user['id']}}">Gestionar permisos</x-tooltip>
                   </a>  
                </div>
            @endcan
        @endif
        @can('Borrar Usuarios')
       
           @livewire('users.delete-user', ['user' => $user], key($user['id']))
        @endcan
    </div>

</div>
