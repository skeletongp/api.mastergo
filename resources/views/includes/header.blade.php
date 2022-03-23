<nav
    class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800 flex flex-row justify-between items-center ">
    <a href="{{route('home')}}" class="flex items-center">
        <span class="fab fa-drupal text-4xl text-blue-500 mr-2"></span>
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{ env('APP_NAME') }}</span>
    </a>
    <div class="container flex flex-wrap justify-end items-center mx-auto ">

        <div class="hidden md:flex md:order-2 ml-4">
            <div class="  mr-3 md:mr-0 w-56">
                <livewire:general.search-field />
            </div>
        </div>
        <div class=" items-center px-4 flex md:w-auto md:order-1">
            <ul class="flex  mt-4 flex-row space-x-4 md:mt-0 md:text-sm md:font-medium">
                <x-dropdown>
                    <x-slot name="title">
                        <span class="fas fa-plus mr-2"></span>
                        Añadir
                    </x-slot>
                    <li class="my-2 ">
                        <livewire:users.create-user />
                    </li>
                </x-dropdown>

            </ul>
        </div>
    </div>
</nav>
