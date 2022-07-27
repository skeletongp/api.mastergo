@props(['activeRoute' => '', 'routeName', 'icon', 'text', 'scope' => ''])

<li>
    <a href="{{route($routeName)}}"
        class="flex load items-center mt-2 p-2 text-base font-normal {{ request()->routeIs($routeName) ? ' border-b-2 border-cyan-700 ' : ' border-b-2 border-gray-200' }}   hover:bg-gray-300 hover:text-gray-700  ">
        <span class="{{ $icon }} text-xl"></span>
        <span class="ml-3">{{ $text }}</span>
    </a>
</li>

