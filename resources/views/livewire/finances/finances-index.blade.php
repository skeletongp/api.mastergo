<div class="w-full flex  items-start">
    @slot('rightButton')
        <div class="flex space-x-4 items-center">
            @livewire('cheques.create-cheque')
            @livewire('banks.create-bank')
            @livewire(
                'contables.create-count',
                [
                    'model' => null,
                    'chooseModel' => true,
                    'codes' => App\Models\CountMain::get()->pluck('code')->toArray(),
                ],
                key(uniqid()),
            )
        </div>
    @endslot
    <div class="  sticky top-24 pt-8 " style="width: 34rem; max-width: 34rem">
        <div
            class="w-full text-lg font-medium text-gray-900  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white ">
            <div aria-current="true"
                class="block w-full px-4 py-2 pb-3 text-gray-800 bg-gray-100  rounded-tl-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-xl uppercase text-center font-bold ">
                Cuentas
            </div>
            <div class="w-full py-4">
                <hr>
            </div>
            <div wire:click="changeView('banks.bank-list')" id="divBank"
                class="flex flex-row items-center space-x-2 relative w-full px-4 my-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'banks.bank-list' ? 'bg-blue-100' : '' }}">
                <span class="far fa-university text-xl w-8 text-center"></span>
                <span class=" text-lg">Cuentas de Banco</span>
                <hr>
            </div>
            <div wire:click="changeView('cheques.cheque-list')" id="divBank"
                class="flex flex-row items-center space-x-2 relative w-full px-4 my-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'cheques.cheque-list' ? 'bg-blue-100' : '' }}">
                <span class="far  fa-money-check-alt text-xl w-8 text-center"></span>
                <span class=" text-lg">Cheques</span>
                <hr>
            </div>
            <div wire:click="changeView('finances.por-cobrar')" id="divBank"
                class="flex flex-row items-center space-x-2 relative w-full px-4 my-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'finances.por-cobrar' ? 'bg-blue-100' : '' }}">
                <span class="far  fa-hand-holding-usd text-xl w-8 text-center"></span>
                <span class=" text-lg">Ctas. Por Cobrar</span>
                <hr>
            </div>
            <div wire:click="changeView('finances.por-pagar')" id="divBank"
                class="flex flex-row items-center space-x-2 relative w-full px-4 my-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'finances.por-pagar' ? 'bg-blue-100' : '' }}">
                <span class="far  fa-hand-holding-usd transform fa-flip-horizontal text-xl w-8 text-center"></span>
                <span class=" text-lg">Ctas. Por Pagar</span>
                <hr>
            </div>
            <div wire:click="changeView('finances.cuadres-hist')" id="divBank"
            class="flex flex-row items-center space-x-2 relative w-full px-4 my-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'finances.cuadres-hist' ? 'bg-blue-100' : '' }}">
            <span class="far  fa-history text-xl w-8 text-center"></span>
            <span class=" text-lg">Hist. Cuadres</span>
            <hr>
        </div>
        </div>
    </div>
    <div class="w-full h-full relative  p-4" x-data="{ open: true }">

        <button
            class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
            wire:loading>
            <x-loading></x-loading>
        </button>


        @switch($componentName)
            @case('banks.bank-list')
                <div class="max-w-xl mx-auto">
                    @livewire($componentName)
                </div>
            @break

            @case('cheques.cheque-list')
                <div class=" mx-auto">
                    @livewire($componentName)
                </div>
            @break

            @case('finances.por-cobrar')
                <div class=" mx-auto">
                    @livewire($componentName)
                </div>
            @break

            @case('finances.por-pagar')
                <div class=" mx-auto">
                    @livewire($componentName)
                </div>
            @break
            @case('finances.cuadres-hist')
            <div class=" mx-auto">
                @livewire($componentName)
            </div>
        @break
            @default
        @endswitch
    </div>

</div>
