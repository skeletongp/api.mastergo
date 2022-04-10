<div>
    <p class="leading-relaxed mb-8">{!! $recurso->description !!}</p>
    <div class="mt-8">
        @foreach ($procesos as $ind=> $proceso)
            <div class="flex border-t border-gray-200 py-2">
                <span class="ml-auto text-gray-900 font-bold mb-2 text-base">{!! $proceso !!}</span>
            </div>
        @endforeach
    </div>
</div>
