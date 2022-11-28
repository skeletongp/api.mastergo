<div class="flex flex-col items-center justify-center space-y-4">
    @if (optional($lastPayment)->image)
        <h1 class="font-bold uppercase text-xl ">Adjunto de la factura</h1>
        <img class="w-2/3" src="{{ $lastPayment->image->path }}" alt="">
    @else
        <img class="w-1/3" src="{{ env('NO_IMAGE') }}" alt="">
    @endif
</div>
