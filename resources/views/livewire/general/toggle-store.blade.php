<div class="px-2" style="z-index:150">
    <div class="flex items-center space-x-4">
        @if ($label)
            <span class="w-full text-xl font-bold uppercase">Cambiar de negocio</span>
        @endif
        @if ($title)
            <select name="store" id="store" data-tooltip-target="storeTT"
            data-tooltip-placement="right"
                class="uppercase font-bold text-xl appearance-none outline-none border-0 focus:border-0 active:border-0"
                wire:model="store_id">
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}"
                        {{ $store->id === auth()->user()->store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            <x-tooltip id="storeTT">{{auth()->user()->place->name}}</x-tooltip>
        @else
            <select name="store" id="store"
                class="bg-gray-50  text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 overflow-hidden overflow-ellipsis whitespace-nowrap"
                wire:model="store_id">

                @foreach ($stores as $store)
                    <option value="{{ $store->id }}"
                        {{ $store->id === auth()->user()->store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
</div>
