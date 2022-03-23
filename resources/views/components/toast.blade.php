@props([ 'icon'])
<div x-data="{open: true}">

    <div {{ $attributes->merge(['class' =>'flex items-center p-4 w-full max-w-xs text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800']) }}
        role="alert" x-show="open">
        <div
            class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
            <span class="{{ $icon }}"></span>
        </div>
        <div class="ml-3 mr-2 text-sm font-normal overflow-hidden overflow-ellipsis whitespace-nowrap">
            {{ $text }}
        </div>
        <button type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 flex items-center justify-center  dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" @click="open = ! open">
            <span class="sr-only">Close</span>
            <span class="fas fa-times text-red-500"></span>
        </button>
    </div>
</div>
