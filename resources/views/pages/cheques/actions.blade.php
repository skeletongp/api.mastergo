<div class="flex space-x-2 justify-center">
    @livewire('cheques.deposit-cheque', ['cheque_id' => $id,'status'=>'Pago','type'=>$type], key(uniqid()))
    @livewire('cheques.deposit-cheque', ['cheque_id' => $id,'type'=>$type], key(uniqid().$id))
</div>