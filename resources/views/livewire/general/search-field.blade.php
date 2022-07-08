    <div class="absolute h-max top-3 w-56" x-data="{ open: true }" style="z-index: 1600">
        <div @click.away="open= false">
            <x-input label="Buscar" wire:model="search" class="relative w-56 max-w-xs" @focus="open=true" type="search">
               {{--  <x-slot name="icon">
                    <span class="fas fa-search text-gray-500"></span>
                </x-slot> --}}
            </x-input>
            @if ($searchResults)
                <div class="bg-white   shadow-xl text-sm" x-show="open" >
                    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
                        @can('Ver '.$models[$type])
                        <div class="shadow-lg p-3" >
                            <h2 class="font-bold uppercase mt-2">{{ $models[$type] }}</h2>
                            <hr>
                            <ul>
                                @foreach ($modelSearchResults as $ind => $searchResult)
                                    @if ($ind < 5)
                                        <li class="p-1 hover:bg-gray-100 hover:text-blue-600">
                                            <a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endcan
                    @endforeach
                </div>
            @endif
        </div>
    </div>
