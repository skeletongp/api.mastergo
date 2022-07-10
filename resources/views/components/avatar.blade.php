<div>
    <div class="hidden lg:block">
        @isset($url)
            <a href="{{ $url }}" class="load">
                <img class=" w-8 h-8 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $avatar }}" alt="Avatar">
            </a>
        @else
            <img class="w-8 h-8 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="{{ $avatar }}" alt="Avatar">
        @endisset
    </div>
    <div class="lg:hidden">
        @isset($url)
            <a href="{{ $url }}" class="load">
               <span class="fas fa-eye"></span>
            </a>
        @else
        <span class="fas fa-eye"></span>
        @endisset   
    </div>    
</div>