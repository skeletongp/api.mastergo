<div>

    <div x-cloak class=" z-50 mx-auto ">

        <div
            class=" overflow-y-auto overflow-x-hidden  w-full z-50 justify-center items-center lg:h-full lg:inset-0  mx-auto ">
            <div class="relative  rounded-lg min-w-full  dark:bg-gray-700 ">
                <div class="flex justify-between items-center py-4 px-6 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                        Nueva Cuenta de Banco </h3>
                </div>

                <div class="p-4 w-full">
                    <form action="" class="p-2 w-full" wire:submit.prevent="createBank">
                        <div class="w-full space-y-6  flex flex-col items-end">
                            <div class="space-y-12 w-full">
                                <div class="flex space-x-4 items-center">
                                    <div class="w-full">
                                        <x-base-input class="text-base" label="Nombre del banco"
                                            wire:model.defer="form.bank_name" id="form.bank.name">
                                        </x-base-input>
                                        <x-input-error for="form.bank_name">Campo obligatorio</x-input-error>
                                    </div>
                                    <div class="w-full">
                                        <x-base-input class="text-base" label="No. de Cuenta"
                                            wire:model.defer="form.bank_number" id="form.bank.number">
                                        </x-base-input>
                                        <x-input-error for="form.bank_number">Campo obligatorio y único</x-input-error>
                                    </div>
                                    <div class="w-full">
                                        <x-base-select label="Usuario titular" id="titular_id" 
                                            wire:model.defer="form.titular_id">
                                            <option></option>
                                            @foreach ($users as $id => $user)
                                                <option value="{{ $id }}">{{ $user }}</option>
                                            @endforeach
                                        </x-base-select>
                                        <x-input-error for="form.titular_id">Campo obligatorio</x-input-error>
                                    </div>
                                </div>
                                {{-- Botón --}}
                                <div class="flex my-4  items-center justify-end ">
                                    <x-button
                                        class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                                        wire:loading.attr="disabled">
                                        <div class="animate-spin mr-2" wire:loading wire:target="createBank">
                                            <span class="fa fa-spinner ">
                                            </span>
                                        </div>
                                        Guardar
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
