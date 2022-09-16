<div>
    <button  wire:click="confirm('¿Eliminar usuario', 'deleteUser', {{$user_id}}, 'Borrar Usuarios')">
        <span class="fas fa-trash-alt text-red-500" data-tooltip-target="deleteId{{ $user_id}}"
           ></span>
        <x-tooltip id="deleteId{{ $user_id }}"> Eliminar registro </x-tooltip>
    </button>
</div>