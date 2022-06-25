<div>
    <button  wire:click="confirm('¿Eliminar usuario', 'deleteUser', {{$user['id']}}, 'Borrar Usuarios')">
        <span class="fas fa-trash-alt text-red-500" data-tooltip-target="deleteId{{ $user['id'] }}"
           ></span>
        <x-tooltip id="deleteId{{ $user['id'] }}"> Eliminar registro </x-tooltip>
    </button>
</div>