<div>
    <x-modal title="Anular Gasto" :listenOpen="true">
        <x-slot name="button"> 
            <i class="far fa-trash-alt text-red-400"></i>
        </x-slot>
        <div>
            <form action="" id="delete{{ $outcome_id }}" wire:submit.prevent="deleteOutcome" class="space-y-4 text-left">
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-datalist inputId="debitable{{ $outcome_id }}" listName="debitableList{{ $outcome_id }}"
                            model="debitableId" label="Cuenta del Debe">
                            @foreach ($debitables as $ide => $deb)
                                <option data-value="{{$ide }}"
                                    value="{{ellipsis($deb, 25) }}">
                                </option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="debitableId"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist inputId="creditable{{ $outcome_id }}" listName="creditableList{{ $outcome_id }}"
                            model="creditableId" label="Cuenta del Haber">
                            @foreach ($creditables as $code => $cred)
                                <option data-value="{{ $code }}"
                                    value="{{ellipsis($cred, 25) }}">
                                </option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="creditableId"></x-input-error>
                    </div>
                </div>

                <div class="w-full flex justify-end" form="delete{{ $outcome_id }}">
                    <x-button class="bg-red-400 text-white">
                        Anular
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
