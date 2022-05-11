<div>
    <x-modal open="{{false}}" title="Se requiere autorización" fitVerticalContainer="true" hideButton="true">
        <x-slot name="button">
            <div id="openAuthorize"></div>
        </x-slot>
        <form action="" wire:submit.prevent="authorizeAction('{{$action}}')" class="space-y-4">
            <div>
                <x-base-select label="Usuario" id="hashedPassword" wire:model.defer="hashedPassword">
                    <option value=""></option>
                    @foreach ($this->admins as $name => $pwd)
                        <option value="{{ $pwd }}">{{ $name }}</option>
                    @endforeach
                </x-base-select>
            </div>
            <div>
                <x-base-input wire:model.defer="unhashedPassword" label="Contraseña" id="unhashedPassword"
                    type="password" autocomplete="off">
                </x-base-input>
                <x-input-error for="unhashedPassword">Ingrese una contraseña</x-input-error>
            </div>
            <div class="flex justify-end">
                <x-button class="bg-gray-100 text-gray-800">
                    Autorizar
                </x-button>
            </div>
        </form>
    </x-modal>
    @push('js')
        <script>
            Livewire.on('openAuthorize', function(){
                $('#openAuthorize').click();
            })
        </script>
    @endpush
</div>
