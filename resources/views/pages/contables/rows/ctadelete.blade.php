<div>
    <table class="w-full">
        @forelse ($counts as $count)
            <tr class=" border-b ">
                @if ($count['borrable'])
                    <td scope="row" class=" whitespace-nowrap px-2 pb-1">
                        @livewire('general.delete-model', ['class' => 'App\Models\Count', 'model_id' => $count['id'], 'msg' => '¿Desea borrar esta cuenta?', 'permission' => 'Borrar Cuentas'], key(uniqid()))
                    </td>
                @else
                <td scope="row" class="py-1 whitespace-nowrap px-2">
                    <span class="fas fa-ban text-red-400 opacity-50"></span>
                </td>
                @endif
            </tr>
        @empty
            <h1 class="text-center">
                - - -
            </h1>
        @endforelse
    </table>
</div>
