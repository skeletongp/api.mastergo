<div>
    <div class="flex flex-row justify-around items-center space-x-2">
       
              
            @can('Borrar Comprobantes')
                <livewire:general.delete-model :model="$comprobante" event="refreshLivewireDatatable" title="Comprobante"
                    permission="Borrar Comprobantes" :wire:key="uniqid().'idd2'" />
            @endcan
    </div>

</div>
