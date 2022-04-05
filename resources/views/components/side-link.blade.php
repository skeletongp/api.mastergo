@props(['activeRoute' => '', 'routeName', 'icon', 'text', 'scope' => ''])
@scope($scope)
<li>
    <a href="{{ route($routeName) }}"
        class="flex  items-center rounded-xl mt-2 p-2 text-lg font-normal {{ request()->routeIs($activeRoute) ? 'text-blue-100 bg-gray-800 ' : 'text-gray-900' }}   hover:bg-gray-300 hover:text-gray-700  ">
        <span class="{{ $icon }} text-xl"></span>
        <span class="ml-3">{{ $text }}</span>
    </a>
</li>
@endscope
