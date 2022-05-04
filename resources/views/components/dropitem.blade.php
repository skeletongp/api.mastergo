@props(['text', 'routes' => [], 'icon', 'scope'=>''])
@scope ($scope)
<div class=" select-none" x-data="{ open: false }" @click.away="open = false" x-cloak>
    <li>
        <div @click="open = !open"
            class="flex cursor-pointer items-center  p-2 pb-4 text-base font-normal {{ request()->routeIs($routes) ? ' border-b-2 border-cyan-700 ' : ' border-b-2 border-gray-200' }} hover:border-cyan-600 hover:bg-gray-300 hover:text-gray-700  ">
            <div class="flex space-x-3 items-center">
                <span class="{{ $icon }} w-6 text-center text-xl"></span>
                <span class="">{{ $text }}</span>
            </div>
        </div>
    </li>
    <div class="  w-max z-50 " x-show="open" x-transition:enter="transition ease-out duration-400" x-cloak
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-400" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95">
        <div class="bg-white w-max absolute shadow-xl pl-1 pr-2 pb-3 rounded-xl z-50">
            {{ $slot }}
        </div>
    </div>
   
</div>
@endscope
