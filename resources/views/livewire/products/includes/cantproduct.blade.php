<div class="flex justify-end mt-8">

</div>
<div class=" bottom-0 absolute w-full">
    <div class="p-2 pr-0 flex justify-between w-full">
        <x-button wire:click="$set('activeTab','unitsproduct')" disabled="{{ $activeTab == 'infoproduct' }}"
            class="uppercase disabled:bg-gray-200 text-xs" wire:loading.attr='disabled'>
            Anterior
        </x-button>
        <x-button form="form1" class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
            wire:loading.attr='disabled'>
            <div class="animate-spin mr-2" wire:loading wire:target="createProduct">
                <span class="fa fa-spinner ">
                </span>
            </div>
            <span>Guardar</span>
        </x-button>
    </div>
</div>
