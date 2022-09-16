<div x-data="show: true" x-init x-cloak>
    
    <div class="flex flex-row justify-start items-center space-x-2">
        @can('Editar Usuarios')
            <div>
                <livewire:users.edit-user  :user_id="$user" :wire:key="uniqid()" />
            </div>
        @endcan

 
            @can('Asignar Permisos')
                <div>
                   <a href="{{route('users.setPermissions', $user)}}">
                    <span class="far fa-shield-check fa-xl text-yellow-600" data-tooltip-target="assignPermission{{$user}}"
                   ></span>
                    <x-tooltip id="assignPermission{{$user}}">Gestionar permisos</x-tooltip>
                   </a>  
                </div>
            @endcan
        @can('Borrar Usuarios')
       
           @livewire('users.delete-user', ['user_id' => $user], key($user))
        @endcan 
    </div>

</div>
