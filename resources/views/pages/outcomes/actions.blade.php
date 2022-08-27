<div>
    @can('Borrar Gastos')
            <livewire:outcomes.delete-outcome :outcome="$outcome" :debitables="$debitables" :creditables="$creditables"
                :wire:key="$outcome['id']" />
      
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endcan
</div>
