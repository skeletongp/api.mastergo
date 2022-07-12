@props(['label', 'labelClass' => '', 'inputClass' => '',  'status' => ' '])
@php
if (empty($attributes['id'])) {
    $attributes['id'] = 'input';
}
@endphp
<div class="">
    <label for="{{ $attributes['id'] }}"
        class="block text-base pb-2 font-medium text-gray-900 dark:text-gray-300 {{ $labelClass }}">{{ $label }}</label>
    <input {{$status}} step="any" {{empty($attributes['type']) ?'text'  : ''}}
        {{ $attributes->merge(['class' =>"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 
        focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 capitalize {$inputClass}"]) }}>

</div>
