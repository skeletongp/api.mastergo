<div>
    <div class="flex space-x-4 p-8">
        <div class="w-full max-w-5xl mx-auto">
            @can('Ver Producciones')
                @can('Crear Producciones')
                @if ($proceso->formulas->count())
                <div class="flex justify-between items-center">
                    <x-toggle id="status" wire:model='status' value='Completado' label='{{ $statusTitle }}'></x-toggle>
                    @livewire('productions.create-production', ['proceso' => $proceso], key($proceso->id))
                </div>
                @endif
                @endcan
                <div class="">
                    @if ($status)
                        <livewire:productions.table-production :proceso="$proceso" :status="$status" />
                    @else
                        <livewire:productions.table-production :proceso="$proceso" :status="$status" />
                    @endif
                </div>
            @else
                <div class="p-8">
                    <h1 class="p-8 text-center font-bold uppercase text-lg">No tienes permisos para ver estos detalles</h1>
                </div>
            @endcan
        </div>
    </div>
</div>
