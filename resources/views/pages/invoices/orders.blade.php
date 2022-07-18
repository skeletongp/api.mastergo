<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices.orders') }}
    @endslot
 
    <div class=" w-full h-full">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
            
         <div class="max-w-5xl w-full h-full py-12 mx-auto relative">
           
            <livewire:invoices.order-view />
            <span class="fas absolute right-0 top-0 cursor-pointer hover:text-blue-500 fa-sync-alt"
            wire:click.prevent="$emit('refreshLivewireDatatable')"
        ></span>
         </div>
        </div>
    </div>
    @include('livewire.invoices.includes.invoice-js')
  
</x-app-layout>
