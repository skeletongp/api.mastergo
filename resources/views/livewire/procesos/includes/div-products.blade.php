   <div class="pt-10 w-full">
       <h1 class="text-left uppercase text-xl font-bold my-2">Productos esperados</h1>
       <form action="" wire:submit.prevent="addProduct">
           <div class="flex space-x-4 items-end w-full ">
               <div class="w-full max-w-xs space-y-2">
                   <label for="frProduct.id" class="text-xl font-medium ">Producto a generar</label>
                   <x-select id="frProduct.id" wire:model="productId" required>
                       <option value=""></option>
                       @foreach ($products as $product)
                           <option value="{{ $product->id }}">{{ $product->name }}</option>
                       @endforeach
                   </x-select>
               </div>
               <div class="w-full max-w-xs space-y-2">
                   <label for="frProduct.unit" class="text-xl font-medium ">Medida</label>
                   <x-select id="frProduct.unit" wire:model="productUnit" required>
                       <option value=""></option>
                       @if ($units)
                           @foreach ($units as $unit)
                               <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                           @endforeach
                       @endif
                   </x-select>
                   <x-input-error for="ProductUnit"></x-input-error>
               </div>
               <div>
                   <x-input type="number" required min="1" label="Esperado " wire:model.defer="productDue"
                       id="frProduct.due">
                   </x-input>
                   <x-input-error for="productDue"></x-input-error>
               </div>
           </div>
           <x-input-error for="fProducts">Añada un producto</x-input-error>

       </form>
       <div class=" overflow-x-auto shadow-md sm:rounded-lg" x-data="{ selectAll: false, open: false }">
           <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
               <thead class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                   <tr>
                       <th scope="col" class="px-6 py-3">
                           Producto
                       </th>
                       <th scope="col" class="px-6 py-3">
                           Cant. Esperada
                       </th>
                       <th scope="col" class="px-6 py-3 text-center">
                           <span class="sr-only">
                               Acciones
                           </span>
                       </th>

                   </tr>
               </thead>
               <tbody class="text-base">
                   @if (count($fProducts))
                       @foreach ($fProducts as $product)
                           <tr
                               class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                               <th scope="row" wire:click="$set('productId', {{$product['product_id']}})"
                                   class="px-6 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                   {{ $product['name'] }}
                               </th>
                               <td class="px-6 py-2  cursor-pointer">
                                   {{ $product['due'] . ' ' . $product['unitname'] }}
                               </td>
                               <td class="px-6 py-2">
                                   <div class="flex space-x-4 w-max mx-auto ">
                                       <span class="far fa-trash-alt text-red-500"
                                           wire:click="removeProduct({{ $product['product_id'] }})">
                                       </span>
                                   </div>
                               </td>
                           </tr>
                       @endforeach
                   @else
                       <tr>
                           <td colspan="3">
                               <h1 class="uppercase text-center font-bold text-xl py-4">No ha seleccionado ningún
                                   producto esperado
                               </h1>
                           </td>
                       </tr>
                   @endif

               </tbody>
           </table>

       </div>
   </div>
