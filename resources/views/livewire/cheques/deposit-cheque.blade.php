<div>
   <x-modal  title="Gestionar Cheque"  maxWidth="max-w-xl">
    <x-slot name="button">
        <span class="fas fa-check text-green-500"></span>
    </x-slot>
    
    <div>
       
        <form action="" wire:submit.prevent="depositCheque">
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between mb-4">
                    <span class="uppercase font-medium text-base">{{$status?:'Cancelado'}}</span>
                    <x-toggle  id="deposit{{$cheque['id']}}" wire:model="status" value="Pago">
                        <x-slot name="label">
                            <span class="fas fa-check-circle text-green-500"></span>
                        </x-slot>
                        <x-slot name="label2">
                            <span class="fas fa-ban text-red-400"></span>
                        </x-slot>
                    </x-toggle>
                </div>
                <div class="flex space-x-4 items-start text-left">
                    <div class="w-full">
                        <x-datalist listName="countList{{$cheque['id']}}" inputId="countId{{$cheque['id']}}" model="count_id" label="Cuenta destino">
                            @foreach ($counts as $id=>$count)
                                <option data-value="{{$id}}" value="{{$count}}"></option>
                                
                            @endforeach
                        </x-datalist>
                        <x-input-error for="count_id"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input label="Comentario" wire:model.defer="comment" id="comment{{$cheque['id']}}"></x-base-input>
                        <x-input-error for="comment"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button>Guardar</x-button>
                </div>
            </div>
        </form>
    </div>
   </x-modal>
</div>
