<div class="">

    <div
        class="w-full text-gray-900 bg-white border border-gray-200 space-y-2 rounded-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <div
            class="relative select-none flex justify-between items-center w-full px-4 py-2 text-xl font-medium uppercase border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
            <span>Artículos</span>
            <span class="font-bold">{{ formatNumber(count($details)) }}</span>

        </div>
        <div
            class="relative select-none flex justify-between items-center w-full px-4 py-2 text-xl font-medium uppercase border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
            <span>Monto Bruto</span>
            <span class="font-bold"> ${{ formatNumber(array_sum(array_column($details, 'subtotal'))) }}</span>
        </div>
        <div
            class="relative select-none flex justify-between items-center w-full px-4 py-2 text-xl font-medium uppercase border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
            <span>Descuento</span>
            <span class="font-bold">
                ${{ formatNumber(array_sum(array_column($details, 'discount'))) }}</span>
        </div>
        <div
            class="relative select-none flex justify-between items-center w-full px-4 py-2 text-xl font-medium uppercase border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
            <span>Impuestos</span>
            <span class="font-bold">
                ${{ formatNumber(array_sum(array_column($details, 'totalTax'))) }}</span>
        </div>
        <div
            class="relative select-none flex justify-between items-center w-full px-4 py-2 text-xl font-medium uppercase border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
            <span>Total Neto</span>
            <span class="font-bold">
                ${{ formatNumber(array_sum(array_column($details, 'total'))) }}</span>
        </div>
    </div>

</div>
