<div>
   
    <button wire:click="confirm('¿Anular venta?', 'deleteInvoice', {{$invoice['id']}}, 'Borrar Facturas')">
        <span class="far fa-trash text-red-400"></span>
    </button>
</div>
