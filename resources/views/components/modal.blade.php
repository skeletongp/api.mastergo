@props(['maxWidth' => 'max-w-md', 'open'=>false, 'fitV'=>true, 'title', 'hideButton'=>false, 'zindex'=>'1800', 'minHeight'=>'min-h-[85vh]'])
<div class="w-full " x-data="{ open: {{$open?'true':'false'}} }" x-cloak @keydown.escape.window="open = false">
    {{-- Modal Main Button --}}
    <div class="{{$hideButton?'hidden':''}}">
        <button id="btn{{$attributes['id']}}" type="button" x-on:click.prevent="open = ! open"
            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            {{ $button }}
        </button>

    </div>
    {{-- Modal --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="opacity-100 -translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 -translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full" @click.away="open = false"
        class="w-full z-50 flex justify-center items-center bg-gray-900 bg-opacity-5  h-full lg:inset-0 {{$fitV?'':$minHeight}} mx-auto absolute " style="z-index: {{$zindex}}">

        {{-- Modal Container --}}
        <div
            class=" bg-white rounded-lg flex flex-col p-4 mx-auto w-full {{ $maxWidth }} shadow-sm shadow-blue-500 dark:bg-gray-700 border border-blue-500 overflow-hidden" {{-- @click.away="open=false" --}}>

            {{-- Modal Header --}}
            <div class="flex justify-between ">
                <h1 class="font-bold my-4 uppercase text-lg  text-left">{{ $title }}</h1>
                <div class="flex space-x-4 items-center text-red-600 cursor-pointer " x-on:click.prevent="open = ! open">
                    <small>Cerrar</small>
                    <span class="fas fa-times " ></span>
                </div>
            </div>
            {{-- Modal Body --}}
            <div class="w-full mt-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
