<div class="w-max">
    <div class="flex space-x-4 items-center">
        @livewire('invoices.order-confirm', ['invoice' => $invoice, 'banks' => $banks], key(uniqid()))
      @livewire('invoices.inv-preview', ['invoice' => $invoice], key('re'.uniqid()))
       
    </div>
</div>
