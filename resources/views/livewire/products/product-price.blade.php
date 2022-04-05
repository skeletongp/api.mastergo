<div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Unidad
                </th>
                <th scope="col" class="px-6 py-3">
                    Valores
                </th>


            </tr>
        </thead>
        <tbody class="text-base">
            @foreach ($price as $ind => $priz)
                <tr
                    class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                    <th scope="row"
                        class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap uppercase cursor-pointer">
                        {{ $ind }}
                    </th>
                    <td class="px-6 py-2  cursor-pointer">
                        {!! $priz !!}
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>
