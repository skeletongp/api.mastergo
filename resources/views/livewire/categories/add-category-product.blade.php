
<div>
    <x-modal title="Registrar Nueva Categorías" :fitV="false">
        <x-slot name="button">
            <div class="flex space-x-2 items-center">
                <span class="fas fa-plus">
                </span>
                <span>Añadir Productos</span>
            </div>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="addCategoryProducts">
                <div class="flex flex-col space y-4">
                    <div>
                        <x-datalist value="{{ $product_name }}" class="border-none h-full" :inputId="'pr_name'"
                            model="product_code" type="search" placeholder="Producto" listName="pr_code_name"
                            wire:keydown.enter.prevent="$emit('focusCant')">
                            @foreach ($products as $index => $prod)
                                <option class="bg-gray-200 " value="{{ $index }} {{ $prod }}"
                                    data-value="{{ $index }}">
                                </option>
                            @endforeach
                        </x-datalist>
                    </div>
                    <div class="flex justify-end py-2">
                        <x-button>
                            Guardar
                        </x-button>
                    </div>
                </div>

            </form>
        </div>
        <div>
            {{-- List of selectedProducts and span fas time to remove --}}
            <div class="flex flex-col space-y-2">
                @foreach ($selectedProducts as $ind=> $product)
                    <div class="flex justify-between items-center">
                        <span>{{ $product }}</span>
                        <span class="fas fa-times text-red-600" wire:click="removeProduct({{ $ind }})"></span>
                    </div>
                @endforeach
        </div>

    </x-modal>
</div>
