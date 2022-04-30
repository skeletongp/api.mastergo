@props(['label','idTT'=>''])
@php
if (empty($attributes['id'])) {
    $attributes['id'] = 'input';
}
@endphp
<div class="relative z-0 w-full group " >
    <input step="any"
        {{ $attributes->merge(['class' =>'block py-2.5 px-0 w-full text-xl text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer']) }}
        placeholder=" " />
    <label for="{{$attributes['id']}}"
        class="absolute text-lg text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-7 scale-90 top-3 z-20 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-7">{{ $label }}</label>
    <div class="absolute right-2 top-0 h-full">
        <div class="h-full flex items-center">
            @if (isset($icon))
                {{ $icon }}
            @endif
        </div>
    </div>
</div>
