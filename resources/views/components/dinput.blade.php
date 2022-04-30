@props(['label', 'idTT' => ''])
@php
if (empty($attributes['id'])) {
    $attributes['id'] = 'input';
}
@endphp
<div class="">
    <label for="{{ $attributes['id']}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
    {{$label}}
    </label>
    <input step="any"
       {{$attributes->merge(['class'=>'bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])}}>
</div>
