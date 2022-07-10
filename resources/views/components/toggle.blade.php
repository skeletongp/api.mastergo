@props(['label', 'label2' => '', 'checked' => false])
<label for="{{ $attributes['id'] }}" class="flex relative items-center cursor-pointer">

    @if ($label2)
        <span class="mr-3 text-sm font-medium text-gray-900 dark:text-gray-300">{!! $label2 !!}</span>
    @endif
    <div class="relative">
        <input type="checkbox" {{ $attributes }} class="sr-only" {{ $checked ? 'checked' : '' }}>
        <div
            class="w-11 h-6 bg-gray-200 rounded-full border border-gray-200 toggle-bg dark:bg-gray-700 dark:border-gray-600">
        </div>
    </div>
    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{!! $label !!}</span>
</label>
