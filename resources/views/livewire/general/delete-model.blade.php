<div class="">

    <button id="btn{{ $model_id }}" type="button"  wire:click="confirm('{{ $msg }}', 'deleteModel', '{{ $permission }}')"
        class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
        <span class=" cursor-pointer far fa-trash-alt text-red-400"
           ></span>
    </button>


</div>
