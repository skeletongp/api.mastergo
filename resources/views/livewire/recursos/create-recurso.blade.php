<div>
    <x-modal maxWidth="max-w-lg">
        <x-slot name="button">
            <span class="fas w-6 text-center fa-plus mr-2"></span>
            <span> Crear Recurso</span>
        </x-slot>
        <x-slot name="title">Registrar recurso</x-slot>

        <div class="py-4">
            <form action="" wire:submit.prevent="createRecurso" class="space-y-4">
                <div>
                    <x-input label="Nombre del recurso" wire:model.defer="form.name" id="form.r.name"></x-input>
                    <x-input-error for="form.name"></x-input-error>
                </div>
                <div>
                        <x-select wire:model.defer="form.provider_id" id="form.r.provider_id">
                            <option value="">Proveedor</option>
                            @foreach ($providers as $id => $provider)
                            <option value="{{$id}}">{{$provider}}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.provider_id"></x-input-error>
                </div>
                <div >
                    <div wire:ignore class="space-y-2">
                        <label >Descripción del recurso</label>
                        <textarea class="hidden"  id="editor" wire:model.defer="form.description"></textarea>
                    </div>
                    <x-input-error for="form.description"></x-input-error>
                </div>
                <div class="flex space-x-4 items-end">
                    <div class="w-2/4">
                        <x-select wire:model.defer="form.unit_id" id="form.r.unit_id">
                            <option value="">Medida</option>
                            @foreach ($units as $id => $unit)
                            <option value="{{$id}}">{{$unit}}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.unit_id"></x-input-error>
                    </div>
                    <div class="w-1/4">
                        <x-input type="number" id="form.r.cant" label="Cantidad" wire:model.defer="form.cant"></x-input>
                        <x-input-error for="form.cant"></x-input-error>
                    </div>
                    <div class="w-1/4">
                        <x-input type="number" id="form.r.cost" label="Costo" wire:model.defer="form.cost"></x-input>
                        <x-input-error for="form.cost"></x-input-error>
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <div class="">
                        <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                            wire:loading.attr='disabled'>
                            <div class="animate-spin mr-2" wire:loading wire:target="createRecurso">
                                <span class="fa fa-spinner ">
                                </span>
                            </div>
                            <span>Guardar</span>
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
    @push('js')
        <script>
            $(document).ready(function() {
                ClassicEditor
                    .create(document.querySelector('#editor'), {
                        toolbar: ["heading", "|", "bold", "italic", "link", "bulletedList", "numberedList", "|",
                            "blockQuote", "undo", "redo"
                        ]
                    })
                    .then(editor => {
                        $('#editor').removeClass("hidden");
                        editor.config.removePlugins = 'uploadImage'
                        editor.model.document.on('change:data', () => {
                            @this.set('form.description', editor.getData());
                        })
                    })
                    .catch(error => {
                        console.error(error);
                    });

            });
        </script>
    @endpush
</div>