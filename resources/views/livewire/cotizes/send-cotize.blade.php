<div>
    <button id="btnSendCotize{{$cotize}}" type="button" wire:click="sendCotize"
    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
    <span class="fas fa-paper-plane"></span>
</button>
<div wire:loading id="generalLoadCotize">
    <x-loading></x-loading>
</div>
</div>
