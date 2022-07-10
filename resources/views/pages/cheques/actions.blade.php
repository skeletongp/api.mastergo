<div class="flex space-x-4 justify-center">
    @if ($cheque['status'] == 'Pendiente')
    @livewire('cheques.deposit-cheque', ['cheque' => $cheque], key(uniqid()))
        
    @else
        <span class="fas fa-ban text-red-400"></span>
    @endif
</div>