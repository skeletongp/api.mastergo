<div>
    <table class="w-full">
       
        @forelse ($counts as $count)
            <tr class=" border-b  ">
                <td scope="row" class="py-1 whitespace-nowrap px-2">
                    {{ $count['code'] }} {{ ellipsis($count['name'],30) }}
                </td>
            </tr>
            @empty
            <h1 class="text-center">
                - - -
            </h1>
        @endforelse
    </table>
</div>
