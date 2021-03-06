@props(['disabled'=>false])
<button type="submit" {{$disabled?'disabled':''}}
    {{ $attributes->merge(['class' =>' font-medium rounded-lg px-4 py-2.5 text-center inline-flex items-center  shadow-sm hover:text-gray-800 hover:bg-gray-200 bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-400 text-xs ']) }}>{{ $slot }}</button>
