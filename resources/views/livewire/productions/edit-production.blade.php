<div>
    <x-modal title="Gestionar producción">
        <x-slot name="button">
            <span class="far fa-pen text-xl"></span>
        </x-slot>
        <form action="" wire:submit.prevent="updateProduction">
            <div class="p-4">
                <div class="flex space-x-4 items-start">
                    <div>
                        <x-base-input id="edit..{{ $production['id'] }}.setted" wire:model.defer="production.setted"
                            label="Invertido">
                        </x-base-input>
                    </div>
                   {{--  <div>
                        <x-base-input status="{{ count($production['products']) ? '' : 'disabled' }}"
                            id="edit..{{ $production['id'] }}.getted" wire:model.defer="production.getted"
                            label="Obtenido">
                        </x-base-input>
                    </div> --}}
                    @if ( count($production['products']))
                        <div class="">
                            <label class="block text-base pb-4 font-medium text-gray-900 dark:text-gray">Estado</label>
                            <x-toggle id="production.{{ $production['id'] }}.status"
                                label="{{ $status ? 'Completado' : 'Iniciado' }}" wire:model="status"
                                value="Completado">
                            </x-toggle>
                        </div>
                    @endif
                </div>
                @if (!count($production['products']))
                    <p class="text-red-600 text-sm leading-4 py-1">Debe indicar los productos obtenidos, antes de marcarlo como terminado y
                        de colocar lo obtenido en total.</p>
                @endif

                <div class="flex justify-end py-4">
                    <x-button>Actualizar</x-button>
                </div>
            </div>

        </form>
    </x-modal>
</div>
