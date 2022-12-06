    <div class=" h-max w-56" x-data="{ open: true }" style="z-index: 1600">
        <div @click.away="open= false">
            <x-base-input icon="fas fa-search" placeholder="Buscar..."  wire:model.debounce.500ms="search" class="relative lg:py-1 lg:pb-1.5  w-56 max-w-xs" @focus="open=true" type="search">
                {{-- <x-slot name="icon">
                    <span class="fas fa-search text-gray-500"></span>
                </x-slot> --}}
            </x-base-input>
            @if ($searchResults)
                <div class="bg-white   shadow-xl text-sm" x-show="open">
                    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
                        @can('Ver ' . $models[$type])
                            <div class="shadow-lg p-3">
                                <h2 class="font-bold uppercase mt-2">{{ $models[$type] }}</h2>
                                <hr>
                                <ul>
                                    @foreach ($modelSearchResults as $ind => $searchResult)
                                        @if ($ind < 5)
                                            <li class="p-1 hover:bg-gray-100 hover:text-blue-600">
                                                <a class=" load" @click="showLoad()"
                                                    href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
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
        @push('js')
            <script>
                
                    function showLoad() {
                        $('#generalLoad').removeClass('hidden');
                    }
                
            </script>
        @endpush
    </div>
