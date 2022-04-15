@props(['label'])
@php
if (empty($attributes['id'])) {
    $attributes['id'] = 'radio';
}
@endphp
<div class="form-check select-none cursor-pointer">
    <input
        {{ $attributes->merge(['class' =>'form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer']) }}
        type="radio">
    <label class="form-check-label text-sm inline-block max-w-[5rem] text-gray-800  overflow-hidden overflow-ellipsis whitespace-nowrap" for="{{ $attributes['id'] }}">
        {{ $label }}
    </label>
</div>
