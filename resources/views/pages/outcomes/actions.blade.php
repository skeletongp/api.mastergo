<div>
    @can('Borrar Gastos')
        @if (!array_key_exists('ncf',$outcome))
            <livewire:outcomes.delete-outcome :outcome="$outcome" :debitables="$debitables" :creditables="$creditables"
                :wire:key="$outcome['id']" />
        @else
        <span class="fas fa-ban text-red-400"></span>
        @endif
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endcan
</div>
