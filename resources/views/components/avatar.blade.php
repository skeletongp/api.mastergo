@isset($url)
    <a href="{{ $url }}">
        <img class="w-8 h-8 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $avatar }}" alt="Avatar">
    </a>
@else
    <img class="w-8 h-8 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $avatar }}" alt="Avatar">
@endisset
