<div>
    <x-modal :fitV="false" title="Añadir atributo">
        <x-slot name="button">
            Nuevo Atributo
        </x-slot>
        <div>
            <form action=""  wire:submit.prevent="addBrand">
                <div class="flex flex-col space-y-4 ">
                    <div class="flex space-x-4">
                        <div class="w-full">
                           <x-base-input wire:model.defer="name" id="brand_name" label="Nombre del atributo" type="text" />
                           <x-input-error for="name">Campo requerido</x-input-error>
                        </div>
                        <div class="">
                            <x-base-input wire:model.defer="cost" id="brand_cost" label="Costo" type="number" />
                            <x-input-error for="cost">Campo requerido</x-input-error>
                        </div>
                    </div>
                    <div class="flex justify-end py-4">
                        <x-button>Añadir</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>
