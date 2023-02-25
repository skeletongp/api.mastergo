<div class="flex space-x-2 items-center">
    @can('Pagar Gastos')
        <livewire:outcomes.pay-outcome :outcome_id="$outcome_id" :key="$outcome_id" />
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endcan
    @can('Borrar Gastos')
        <livewire:outcomes.delete-outcome :outcome_id="$outcome_id" :key="$outcome_id.'d'" />
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endcan

</div>
