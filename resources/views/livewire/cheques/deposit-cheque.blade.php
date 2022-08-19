<div>
    <x-modal :listenOpen="true" title="Gestionar Cheque" maxWidth="max-w-xl">
        <x-slot name="button">
            @if ($status)
                <span class="fas fa-check-circle text-green-500"></span>
            @else
                <span class="fas fa-times-circle text-red-500"></span>
            @endif
        </x-slot>

        <div>

            <form action="" wire:submit.prevent="depositCheque">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between mb-4">
                        <span class="uppercase font-medium text-base">{{ $status ?: 'Cancelado' }}</span>

                    </div>
                    @if ($cheque['type'] == 'Recibido')
                        <div class="flex space-x-4 items-start text-left">
                            @if ($status)
                                <div class="w-full" wire:target="counts" wire:loading.attr="hidden">
                                    <x-datalist listName="countList{{ $cheque['id'] }}"
                                        inputId="countId{{ $cheque['id'] }}" wire:model="count_id"
                                        label="Cuenta destino">
                                        @foreach ($counts as $id => $count)
                                            <option data-value="{{ $id }}" value="{{ $count }}">
                                            </option>
                                        @endforeach
                                    </x-datalist>
                                    <x-input-error for="code"></x-input-error>
                                </div>
                            @endif
                            <div class="w-full">
                                <x-base-input label="Comentario" wire:model.defer="comment"
                                    id="comment{{ $cheque['id'] }}"></x-base-input>
                                <x-input-error for="comment"></x-input-error>
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-end">
                        <x-button>Guardar</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>
