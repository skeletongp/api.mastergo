<div>

    <div x-cloak class=" z-50 mx-auto ">

        <div
            class=" overflow-y-auto overflow-x-hidden  w-full z-50 justify-center items-center md:h-full md:inset-0  mx-auto ">
            <div class="relative  rounded-lg min-w-full  dark:bg-gray-700 ">
                <div class="flex justify-between items-center py-4 px-6 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                        Crear Sucursal </h3>

                </div>

                <div class="p-4 w-full">
                    <form action="" class="p-2 w-full"
                        wire:submit.prevent="createPlace({{ auth()->user()->store->id }})">
                        <div class="w-full space-y-6  flex flex-col items-end">

                            <div class="space-y-12 w-full">
                                {{-- Nombre y apellidos --}}
                                <div class="flex space-x-4 items-center">
                                    <div class="w-full">
                                        <x-base-input class="text-2xl" label="Nombre de la sucursal"
                                            wire:model.defer="form.name" id="form.place.name">
                                        </x-base-input>
                                        <x-input-error for="form.name"></x-input-error>
                                    </div>
                                    <div class="w-full">
                                        <x-base-input  type="tel" class="text-2xl" label="No. Teléfono"
                                            wire:model.defer="form.phone" id="form.place.phone">
                                        </x-base-input>
                                        <x-input-error for="form.phone"></x-input-error>
                                    </div>
                                    <div class="w-full">
                                        <div class="w-full "></div>
                                        <x-base-select label="Usuario Titular" id="user_id"
                                            wire:model.defer="form.user_id">
                                            <option ></option>
                                            @foreach ($users as $id => $user)
                                                <option value="{{ $id }}">{{ $user }}</option>
                                            @endforeach
                                        </x-base-select>
                                        <x-input-error for="form.user_id"></x-input-error>
                                    </div>
                                </div>
                                {{-- Botón --}}
                                <div class="flex my-4  items-center justify-end ">
                                    <x-button
                                        class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                                        wire:loading.attr="disabled">
                                        <div class="animate-spin mr-2" wire:loading wire:target="createPlace">
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
    @push('js')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Seleccione un responsable",
                    allowClear: true
                });
                $('.select2').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('form.user_id', data);
                });
            });
            Livewire.hook('element.updated', function() {
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Seleccione un responsable",
                        allowClear: true
                    });
                });
                $('.select2').on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('form.user_id', data);
                });
            });
        </script>
    @endpush

</div>
