@props(['activeRoute'=>'', 'routeName', 'icon', 'text'])
<li>
    <a href="{{route($routeName)}}" 
        class="flex items-center p-2 text-lg font-normal {{request()->routeIs($activeRoute)?'text-blue-100 bg-gray-800':'text-gray-900 bg-white'}} rounded-lg  hover:bg-gray-200  ">
       <span class="{{$icon}} text-xl"></span>
        <span class="ml-3">{{$text}}</span>
    </a>
</li>