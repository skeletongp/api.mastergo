<div x-data="{open : false}" @click.away="open = false">
    <button @click="open = true" @mouseover="open = true"
        class="text-gray-600 font-medium rounded-lg text-lg px-4 py-2.5 text-center inline-flex items-center  shadow-sm hover:text-gray-800 hover:bg-gray-200 w-48"
        type="button">
        {{ $title }}
        <span class="fas fa-angle-down ml-2"></span>
    </button>

    <div x-show="open" x-transition:enter.scale.80 x-transition:leave.scale.90
        class=" z-10 absolute w-44 text-lg list-none bg-white rounded divide-y divide-gray-100  dark:bg-gray-700">
        <ul class="py-1" aria-labelledby="dropdownButton">
            {{ $slot }}
        </ul>
    </div>

</div>
