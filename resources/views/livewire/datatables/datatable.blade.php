<div class="max-w-lg md:max-w-7xl">
    @includeIf($beforeTableSlot)
    <div class="relative">
        <div class="flex items-center justify-between mb-1">
            @if ($this->searchableColumns()->count())
                <div class="flex items-center h-10">
                    <div class="flex rounded-lg  shadow-sm w-48 lg:w-[24rem]">
                        <div class="relative flex-grow focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="fas fa-search text-xl text-gray-300"></span>
                            </div>
                            <input wire:model.debounce.500ms="search"
                                class="block w-full py-3 px-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none"
                                placeholder="{{ ellipsis($this->searchableColumns()->map->label->join(', '), 30) }}"
                                type="text" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <button wire:click="$set('search', null)"
                                    class="text-gray-300 hover:text-red-600 focus:outline-none">
                                    <x-icons.x-circle class="w-5 h-5 stroke-current" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            <div class="flex flex-wrap items-center space-x-1">
                <div wire:loading>
                    <x-loading />
                </div>

                @if ($this->activeFilters)
                    <button wire:click="clearAllFilters"
                        class="flex items-center px-3 text-xs font-medium tracking-wider text-red-500 uppercase bg-white border border-red-400 space-x-2 rounded-md leading-4 hover:bg-red-200 focus:outline-none"><span>{{ __('Reset') }}</span>
                        <x-icons.x-circle class="m-2" />
                    </button>
                @endif

                @if (count($this->massActionsOptions))
                    <div class="flex items-center justify-center space-x-1">
                        <select wire:change="massActionOptionHandler" wire:model="massActionOption"
                            class="px-3 py-2.5 text-xs font-medium tracking-wider uppercase bg-white border border-green-400 space-x-2 rounded-md leading-4 focus:outline-none"
                            id="datatables_mass_actions">
                            <option value="">{{ __('Opciones...') }}</option>
                            @foreach ($this->massActionsOptions as $group => $items)
                                @if (!$group)
                                    @foreach ($items as $item)
                                        <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                                    @endforeach
                                @else
                                    <optgroup label="{{ $group }}">
                                        @foreach ($items as $item)
                                            <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        {{-- <button
                            wire:click="massActionOptionHandler"
                            class="flex items-center px-4 py-2 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 rounded-md leading-4 hover:bg-green-200 focus:outline-none" type="submit" title="Submit"
                        >Ir</button> --}}
                    </div>
                @endif

                @if ($exportable)
                    <div x-data="{
                        init() {
                            window.livewire.on('startDownload', link => window.open(link, '_blank'))
                        }
                    }" x-init="init">
                        <button wire:click="export"
                            class="flex items-center px-3 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 space-x-2 rounded-md leading-4 hover:bg-green-200 focus:outline-none"><span>{{ __('Exportar') }}</span>
                            <x-icons.excel class="m-2" />
                        </button>
                    </div>
                @endif

                @if ($hideable === 'select')
                    @include('datatables::hide-column-multiselect')
                @endif

                @foreach ($columnGroups as $name => $group)
                    <button wire:click="toggleGroup('{{ $name }}')"
                        class="px-3 py-2 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 rounded-md leading-4 hover:bg-green-200 focus:outline-none">
                        <span
                            class="flex items-center h-5">{{ isset($this->groupLabels[$name]) ? __($this->groupLabels[$name]) : __('Cambiar :group', ['group' => $name]) }}</span>
                    </button>
                @endforeach
                @includeIf($buttonsSlot)
            </div>
        </div>

        @if ($hideable === 'buttons')
            <div class="p-2 grid grid-cols-8 gap-2">
                @foreach ($this->columns as $index => $column)
                    @if ($column['hideable'])
                        <button wire:click.prefetch="toggle('{{ $index }}')"
                            class="px-3 py-2 rounded text-white text-xs focus:outline-none
                        {{ $column['hidden'] ? 'bg-blue-100 hover:bg-blue-300 text-blue-600' : 'bg-blue-500 hover:bg-blue-800' }}">
                            {{ $column['label'] }}
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        <div wire:loading.class="opacity-50"
            class="rounded-lg mt-4 @unless($complex || $this->hidePagination) rounded-b-none @endunless shadow-lg bg-white max-w-screen overflow-x-scroll border-2 @if ($this->activeFilters) border-blue-500 @else border-transparent @endif @if ($complex) border-b-0 @endif">
            <div>
                <div
                    class="p-4 uppercase text-left flex justify-between items-center w-full font-bold text-xl select-none">
                    @isset($headTitle)
                        {!! $headTitle !!}
                    @else
                        <div>

                        </div>
                    @endisset
                    <span wire:click="$emit('refreshLivewireDatatable')"
                        class="fas fa-sync-alt hover:text-blue-500 cursor-pointer"></span>
                </div>
                <div class="table min-w-full align-middle">
                    @unless($this->hideHeader)
                        <div class="table-row divide-x divide-gray-200">
                            @foreach ($this->columns as $index => $column)
                                @if ($hideable === 'inline')
                                    @include('datatables::header-inline-hide', [
                                        'column' => $column,
                                        'sort' => $sort,
                                    ])
                                @elseif($column['type'] === 'checkbox')
                                    @unless($column['hidden'])
                                        <div
                                            class="justify-center table-cell w-32 h-12  {{ isset($padding) ? $padding : 'py-4 px-6 ' }}  overflow-hidden text-xs font-medium tracking-wider text-left text-gray-500 uppercase align-middle border-b border-gray-200 bg-gray-50 leading-4 focus:outline-none">
                                            <div
                                                class="px-3 py-1 rounded @if (count($selected)) bg-orange-400 @else bg-gray-200 @endif text-white text-center">
                                                {{ count($selected) }}
                                            </div>
                                        </div>
                                    @endunless
                                @else
                                    @include('datatables::header-no-hide', [
                                        'column' => $column,
                                        'sort' => $sort,
                                    ])
                                @endif
                            @endforeach
                        </div>
                    @endunless
                    <div class="table-row bg-blue-100 divide-x divide-blue-200">
                        @foreach ($this->columns as $index => $column)
                            @if ($column['hidden'])
                                @if ($hideable === 'inline')
                                    <div class="table-cell w-5 overflow-hidden align-middle bg-blue-100"></div>
                                @endif
                            @elseif($column['type'] === 'checkbox')
                                @include('datatables::filters.checkbox')
                            @elseif($column['type'] === 'label')
                                <div class="table-cell overflow-hidden align-middle">
                                    {{ $column['label'] ?? '' }}
                                </div>
                            @else
                                <div class="table-cell overflow-hidden align-middle">
                                    @isset($column['filterable'])
                                        @if (is_iterable($column['filterable']))
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.select', [
                                                    'index' => $index,
                                                    'name' => $column['label'],
                                                    'options' => $column['filterable'],
                                                ])
                                            </div>
                                        @else
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.' .
                                                        ($column['filterView'] ?? $column['type']),
                                                    [
                                                        'index' => $index,
                                                        'name' => $column['label'],
                                                    ])
                                            </div>
                                        @endif
                                    @endisset
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @foreach ($this->results as $row)
                        <div class="table-row p-1 {{ $this->rowClasses($row, $loop) }}">
                            @foreach ($this->columns as $column)
                                @if ($column['hidden'])
                                    @if ($hideable === 'inline')
                                        <div
                                            class="table-cell w-5 @unless($column['wrappable']) whitespace-nowrap truncate @endunless overflow-hidden  align-middle">
                                        </div>
                                    @endif
                                @elseif($column['type'] === 'checkbox')
                                    @include('datatables::checkbox', [
                                        'value' => $row->checkbox_attribute,
                                    ])
                                @elseif($column['type'] === 'label')
                                    @include('datatables::label')
                                @else
                                    <div
                                        class="table-cell {{ isset($padding) ? $padding : 'py-4 px-6 ' }} @unless($column['wrappable']) whitespace-nowrap truncate @endunless @if ($column['contentAlign'] === 'right') text-right @elseif($column['contentAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }} align-middle">
                                        {!! $row->{$column['name']} !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    @if ($this->hasSummaryRow())
                        <div class="table-row p-1">
                            @foreach ($this->columns as $column)
                                @unless($column['hidden'])
                                    @if ($column['summary'])
                                        <div
                                            class="table-cell {{ isset($padding) ? $padding : 'py-2 px-6 ' }} @unless($column['wrappable']) whitespace-nowrap truncate @endunless @if ($column['contentAlign'] === 'right') text-right @elseif($column['contentAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }}">
                                            {!! $this->summarize($column['name']) !!}
                                        </div>
                                    @else
                                        <div class="table-cell"></div>
                                    @endif
                                @endunless
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @if ($this->results->isEmpty())
                <p
                    class="p-3 text-lg text-center font-bold uppercase  overflow-hidden overflow-ellipsis whitespace-nowrap">
                    {{ __('No hay ningún resultado para mostrar') }}
                </p>
            @endif
        </div>

        @unless($this->hidePagination)
            <div
                class="max-w-screen bg-white @unless($complex) rounded-b-lg @endunless border-4 border-t-0 border-b-0 @if ($this->activeFilters) border-blue-500 @else border-transparent @endif">
                <div class="items-center justify-between py-2 sm:flex">
                    {{-- check if there is any data --}}
                    @if (count($this->results))
                        <div class="flex items-center my-2 sm:my-0">
                            <x-base-select name="perPage" label=""
                                class="block w-full max-w-[6rem] py-2 pl-3  mt-1 mr-2 text-base border-gray-300 form-select leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5"
                                wire:model="perPage">
                                @foreach (config('livewire-datatables.per_page_options', [10, 25, 50, 100]) as $per_page_option)
                                    <option value="{{ $per_page_option }}">{{ $per_page_option }}</option>
                                @endforeach
                                <option value="1000">{{ __('1000') }}</option>
                            </x-base-select>
                        </div>

                        <div class="my-4 sm:my-0">
                            <div class="lg:hidden">
                                <span
                                    class="space-x-2 whitespace-nowrap">{{ $this->results->links('datatables::tailwind-simple-pagination') }}</span>
                            </div>

                            <div class="justify-center hidden lg:flex">
                                <span>{{ $this->results->links('datatables::tailwind-pagination') }}</span>
                            </div>
                        </div>

                        @if (!isset($hideResults))
                            <div class="flex justify-end text-gray-600">
                                {{ $this->results->firstItem() }} -
                                {{ $this->results->lastItem() }} {{ __('de') }}
                                {{ $this->results->total() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif
        </div>

        @if ($complex)
            <div
                class="bg-gray-50 px-4 py-4 rounded-b-lg rounded-t-none shadow-lg border-2 @if ($this->activeFilters) border-blue-500 @else border-transparent @endif @if ($complex) border-t-0 @endif">
                <livewire:complex-query :columns="$this->complexColumns" :persistKey="$this->persistKey" :savedQueries="method_exists($this, 'getSavedQueries') ? $this->getSavedQueries() : null" />
            </div>
        @endif

        @includeIf($afterTableSlot)

        <span
            class="hidden text-sm text-left text-center text-right text-gray-900 bg-gray-100 bg-yellow-100 leading-5 bg-gray-50"></span>
    </div>
