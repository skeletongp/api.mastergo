<div class="w-max">
    <div class="flex space-x-4 items-center">
        @livewire('invoices.order-confirm', ['invoice_id' => $invoice_id, 'banks' => $banks], key(uniqid()))
      @livewire('invoices.inv-preview', ['invoice_id' => $invoice_id], key(uniqid()))
       
    </div>
</div>
