<div>
    <p class="leading-relaxed mb-8">{!! $recurso->description !!}</p>
    <div class="mt-8">
        <div class="grid grid-cols-3 border-t px-2  justify-between border-gray-200 bg-blue-100">
            <span class=" text-gray-900 font-bold py-1 text-base">NOMBRE</span>
            <span class=" text-gray-900 font-bold py-1 text-base text-right">CANT.</span>
            <span class=" text-gray-900 font-bold py-1 text-base text-right">COSTO</span>
        </div>
        @foreach ($brands as $brand)
            <div class="grid grid-cols-3 space-x-4 border-t  border-gray-200 py-2">
                <h1 class=" text-gray-900 font-bold mb-2 text-base">{!! $brand->name !!}</h1>
                <h1 class=" text-gray-900 font-bold mb-2 text-base text-right">{!! formatNumber($brand->cant) !!}</h1>
                <h1 class=" text-gray-900 font-bold mb-2 text-base text-right">${!! formatNumber($brand->cost) !!}</h1>
            </div>
        @endforeach
    </div>
</div>
