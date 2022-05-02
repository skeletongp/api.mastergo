@props(['label', 'labelClass' => '', 'inputClass' => ''])
@php
if (empty($attributes['id'])) {
    $attributes['id'] = 'input';
}
@endphp
<label for="{{$attributes['id']}}" class="block mb-2  font-medium text-gray-900 dark:text-gray-400">{{$label}}</label>
<select 
   {{$attributes->merge(['class'=>"  bg-gray-50 border border-gray-300 text-gray-900  rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 appearance-none"])}}>
    {{$slot}}
</select>
