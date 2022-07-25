<div>
    <button wire:click="printPreview" id="btn{{$invoice['id']}}Prev" type="button" x-on:click.prevent="open = ! open"
            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center ">
            Ver
        </button>
</div>
