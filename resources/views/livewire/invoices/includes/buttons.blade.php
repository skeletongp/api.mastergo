<div class="grid grid-cols-3 gap-4 p-4">
    <div class="w-full h-full flex flex-col items-center font-bold uppercase  justify-center text-center">
        <x-button class="flex flex-col items-center font-bold uppercase rounded-xl py-4 space-y-2 w-full bg-cyan-600" wire:click="sendInvoice">
            <span class="fas fa-save text-4xl"></span>
            <span class="">Facturar</span>
        </x-button>
    </div>
    <div class="w-full h-full flex flex-col items-center font-bold uppercase  justify-center text-center">
        <x-button class="flex flex-col items-center font-bold uppercase rounded-xl py-4 space-y-2 w-full bg-slate-600" wire:click="refresh">
            <span class="fas fa-sync-alt text-4xl"></span>
            <span class="">Reiniciar</span>
        </x-button>
    </div>
    <div class="w-full h-full flex flex-col items-center font-bold uppercase  justify-center text-center">
        <x-button disabled class="flex flex-col items-center font-bold uppercase rounded-xl py-4 space-y-2 w-full bg-teal-600" wire:click="refresh">
            <span class="fas fa-file-alt text-4xl"></span>
            <span class="">Cotizar</span>
        </x-button>
    </div>
</div>