<div>
    <div class="flex flex-col space-y-4 p-4">

        <div>
            <x-datalist placeholder="Seleccionar Clientes" listName="sendCatalogueList" inputId="sendCatalogueId" model="clientCode">
                @foreach ($clients as $code=> $client)
                    <option value="{{$client}}" data-value="{{$code}}"></option>
                @endforeach
            </x-datalist>
        </div>
        
<div class="overflow-x-auto relative">
    @if (count($selected))
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-1.5 px-2">
                   Cód.
                </th>
                <th scope="col" class="py-1.5 px-2">
                    Nombre
                </th>
                <th scope="col" class="py-1.5 px-2">
                    <span class="sr-only"></span>
                </th>
                
            </tr>
        </thead>
        <tbody>
           @foreach ($selected as $item)
           <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="py-1.5 px-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
               {{$item['code']}}
            </th>
            <td class="py-1.5 px-2">
                {{ellipsis($item['name'],20)}}
            </td>
            <td class="py-1.5 px-2">
                <span wire:click="removeItem('{{$item['code']}}')" class="fas fa-times text-red-400"></span>
            </td>
         
        </tr>
           @endforeach
          
        </tbody>
    </table>
    <div class="flex justify-end pb-2 py-4">
        <button wire:click="sendCatalogue"
            class=" right-2 load rounded-sm h-8 w-max px-3 py-1 space-x-2 shadow-xl text-sm flex items-center ">
            <span class="fab fa-whatsapp text-xl"></span>
            <span>Enviar</span>
    </button>
    </div>
    @endif
</div>

    </div>
</div>
