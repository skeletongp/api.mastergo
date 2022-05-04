<div class="p-2 px-4 pb-6 shadow-lg ">
    <h1 class="py-4 font-bold text-center uppercase text-xl">Registrar nuevo producto</h1>

    <div
        class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 ">
        <ul class="flex justify-center flex-wrap -mb-px">
            <li class="mr-2">
                <a wire:click="$set('activeTab','infoproduct')"
                    class="cursor-pointer text-base inline-block p-4  rounded-t-lg border-b-2 {{$activeTab=='infoproduct'?'text-blue-600  border-blue-600 active':'border-transparent'}} ">Datos Generales</a>
            </li>
            <li class="mr-2">
                <a wire:click="$set('activeTab','unitsproduct')"
                    class="cursor-pointer text-base inline-block p-4  rounded-t-lg border-b-2 {{$activeTab=='unitsproduct'?'text-blue-600  border-blue-600 active':'border-transparent'}} "
                    >Medidas y Precios</a>
            </li>
           
            
        </ul>
    </div>

    <div class="pt-4 h-full min-h-[28rem] relative ">
        @include("livewire.products.includes.$activeTab")
    </div>

   
  
</div>
