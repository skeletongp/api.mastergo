<div>
    <p class="leading-relaxed mb-8">{!! $product->description !!}</p>
    <div class="mt-8">
        @foreach ($price as $ind=> $priz)
            <div class="flex border-t border-gray-200 py-2">
                <span class="text-gray-500">{{ $ind }}</span>
                <span class="ml-auto text-gray-900 font-bold mb-2 text-xl">{!! $priz !!}</span>
            </div>
        @endforeach
    </div>
</div>
