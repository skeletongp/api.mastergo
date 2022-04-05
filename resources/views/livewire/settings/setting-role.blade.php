<div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
    @can('Crear Roles')
        <div class="flex mx-auto w-max space-x-2">
            @can('Crear Roles')
                <livewire:settings.roles.create-role />
            @endcan
            @can('Crear Permisos')
                <div class="border  h-281 border-gray-200"></div>
                <livewire:settings.permissions.create-permission />
            @endcan
        </div>

        <br>
        
        <livewire:settings.roles.role-index />
    @endcan
</div>
