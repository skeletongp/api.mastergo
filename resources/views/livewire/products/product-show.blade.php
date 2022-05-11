<div class="w-full ">
    <button
    class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
    wire:loading>
   <x-loading></x-loading>
</button>
    <h1 class="text-gray-900 text-3xl title-font font-medium mb-4">{{ $product->name }}
    </h1>
    <div class="flex mb-4 select-none">
        <div wire:click="setComponent('products.product-detail')"
            class="cursor-pointer flex-grow  border-b-2  {{ $componentName == 'products.product-detail' ? 'text-indigo-500 border-indigo-500' : 'border-gray-300' }}  py-2 text-lg px-1">
            Detalles
        </div>
        <div wire:click="setComponent('products.product-price')"
            class="cursor-pointer flex-grow border-b-2 {{ $componentName == 'products.product-price' ? 'text-indigo-500 border-indigo-500' : 'border-gray-300' }}  py-2 text-lg px-1">
            Precios/Impuestos
        </div>
        <div wire:click="setComponent('products.product-history')"
            class="cursor-pointer flex-grow border-b-2 {{ $componentName == 'products.product-history' ? 'text-indigo-500 border-indigo-500' : 'border-gray-300' }} py-2 text-lg px-1">
            Historial
        </div>
    </div>
   
    @switch($componentName)
        @case('products.product-detail')
            <livewire:products.product-detail :product="$product" :wire:key="uniqid().'det'" />
        @break

        @case('products.product-price')
            <livewire:products.product-price :product="$product" :wire:key="uniqid().'det'" />
        @break

        @case('products.product-history')
            <livewire:products.product-history :product="$product" :wire:key="uniqid().'det'" />
        @break
    @endswitch

</div>
