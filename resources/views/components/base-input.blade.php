@props(['label', 'labelClass' => '', 'inputClass' => '', 'status' => ' '])
@php
    if (empty($attributes['id'])) {
        $attributes['id'] = 'input';
    }
@endphp
<div class="">
    @isset($label)
        <label for="{{ $attributes['id'] }}"
            class="block text-base pb-2 font-medium text-gray-900 dark:text-gray-300 overflow-hidden overflow-ellipsis whitespace-nowrap"{{ $labelClass }}">{{ $label }}</label>
    @endisset
    <div class="flex space-x-0 items-center rounded-md border focus-within:border-blue-500">
        @isset($icon)
            <span class="mr-2 {{ $icon }}"></span>
        @endisset
        <input {{ $status }} step="any" {{ empty($attributes['type']) ? 'text' : '' }}
            {{ $attributes->merge([
                'class' => "bg-gray-50 border border-gray-300 text-gray-900 text-xs lg:text-sm rounded-md focus:ring-0 focus:border-none 
                 block w-full p-2.5   {$inputClass}",
            ]) }}>
    </div>

</div>
