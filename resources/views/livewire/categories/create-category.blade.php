<div>
    <x-modal title="Registrar Nueva Categorías" :fitV="false">
        <x-slot name="button">
            <div class="flex space-x-2 items-center">
                <span class="fas fa-plus">
                </span>
                <span>Crear Categoría</span>
            </div>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="createCategory">
                <div class="flex flex-col space y-4">
                    <div>
                        <x-input id="catName" wire:model.defer="form.name" required label="Nombre"></x-input>
                        <x-input-error for="form.name">Campo requerido</x-input-error>
                    </div>
                    <div>
                        <div class="space-y-2 pt-7">
                            <label class="font-medium">Descripción de la categoría </label>
                            <textarea rows="3" required
                                class="block p-2.5 w-full text-base text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 resize-none"
                                placeholder="Breve descripción de la categoría" id="form.description" wire:model.defer="form.description"></textarea>
                        </div>
                        <x-input-error for="form.description"></x-input-error>
                    </div>
                    <div class="flex justify-end py-2">
                        <x-button>
                            Guardar
                        </x-button>
                    </div>
                </div>

            </form>
        </div>

    </x-modal>
</div>
