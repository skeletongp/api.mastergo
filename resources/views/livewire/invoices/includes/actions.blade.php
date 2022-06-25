<div class="flex space-x-4 justify-center">
    <div>
        <span class="fas fa-eye cursor-pointer" wire:click="$emit('setPDF','{{$value}}')"><span/> 

    </div>
    <div>
        @livewire('invoices.cancel-invoice', ['invoice_id' => $value], key(uniqid()))
    </div>
</div>