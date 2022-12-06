<div x-data="{ openActions: false }" x-cloak @click.away="openActions = false" class="lg:w-full">
    <div class="lg:hidden  left-2 inset-y-3">
        <button @click="openActions = !openActions" class="focus:outline-none">
            <span class="far fa-lg fa-bars"></span>
        </button>
    </div>
    <ul class="list-none px-2 py-1 lg:py-0 left-0 top-0 bg-white flex flex-col space-y-2 lg:flex-row absolute lg:static lg:space-y-0 lg:space-x-2 lg:items-center lg:justify-end transform lg:translate-x-0 transition-all duration-300 ease-linear w-screen lg:w-full "
        :class="openActions ? 'translate-x-0' : '-translate-x-full'">
        {{ $content }}
    </ul>

</div>
