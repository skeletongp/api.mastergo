<div class="max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
    <a href="#">
        <div class="bg-center mx-auto bg-cover w-28 h-28 rounded-full">
            <span class="{{$icon}}"></span>
        </div>

    </a>
    <div class="p-5">
        <a href="#">
            <h5
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white w-full overflow-hidden overflow-ellipsis whitespace-nowrap">
                {{ $title }}</h5>
        </a>
        <a 
            class="inline-flex items-center justify-end space-x-4 py-2 px-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <span> {{$value}}</span>
            
        </a>
    </div>
</div>
