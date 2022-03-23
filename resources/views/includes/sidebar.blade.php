<aside class="w-64" aria-label="Sidebar">
    <div class="overflow-y-auto py-4 px-3 pl-4 bg-gray-50 rounded dark:bg-gray-800">
      
        <ul class="space-y-2">
            <li>
                <a href="#" class="uppercase flex items-center p-2 text-base font-bold  rounded-lg  hover:bg-gray-200  ">
                    <div class="h-8 w-8 rounded-full shadow-sm bg-cover bg-center "
                        style="background-image: url({{ auth()->user()->avatar }}); min-width:2rem; min-height:2rem">
                    </div>
                    <span class="ml-3 w-full overflow-hidden overflow-ellipsis whitespace-nowrap">{{ auth()->user()->fullname }}</span>
                </a>
            </li>
            <x-side-link routeName='home' icon='fas fa-chart-pie' text='Dashboard' activeRoute="home" />
            <br>
            <x-side-link routeName='users.index' icon='fas fa-users' text='Usuarios' activeRoute="users.*" />
            <hr>
        </ul>
    </div>
</aside>
