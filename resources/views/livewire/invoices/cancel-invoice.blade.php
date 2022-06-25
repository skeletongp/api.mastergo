<div>
    @can('Borrar Facturas')
    <span class="far fa-trash-alt text-red-400 cursor-pointer"  wire:click="confirm('¿Desea anular la factura? Esta acción no se puede deshacer.', 'cancelInvoice', 'Borrar Facturas')"></span>
    @else
    <span class="far fa-ban text-red-400"></span>
    @endcan
</div>
