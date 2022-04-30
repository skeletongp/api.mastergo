@props(['text', 'routes' => [], 'icon', 'scope'=>''])
@scope ($scope)
<div class=" select-none" x-data="{ open: false }" @click.away="open = false" x-cloak>
    <li>
        <div @click="open = !open"
            class="flex cursor-pointer items-center rounded-xl p-2 text-lg font-normal {{ request()->routeIs($routes) ? 'text-blue-100 bg-gray-800 ' : 'text-gray-900' }}   hover:bg-gray-300 hover:text-gray-700  ">
            <div class="flex space-x-3 items-center">
                <span class="{{ $icon }} w-6 text-center text-xl"></span>
                <span class="">{{ $text }}</span>
            </div>
        </div>
    </li>
    <div class="relative  w-full " x-show="open" x-transition:enter="transition ease-out duration-400" x-cloak
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-400" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95">
        <div class="bg-white w-full absolute shadow-xl pl-1 pr-2 pb-3 rounded-xl">
            {{ $slot }}
        </div>
    </div>
    <div class="py-2">
        <hr class="">
    </div>
</div>
@endscope
