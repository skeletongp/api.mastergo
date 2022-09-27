<div>
    @can('Borrar Gastos')
            <livewire:outcomes.delete-outcome :outcome_id="$outcome_id" 
                :key="uniqid()" />
      
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endcan
</div>
