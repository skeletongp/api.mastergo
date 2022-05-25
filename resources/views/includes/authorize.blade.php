<div>
    <x-modal zindex="2000"  title="Se requiere autorización" fitVerticalContainer="true" hideButton="true">
        <x-slot name="button">
            <div id="openAuthorize"></div>
        </x-slot>
        <form action="" wire:submit.prevent="authorizeAction('{{$action}}')" class="space-y-4 text-left">
            <div>
                <x-base-select label="Usuario" id="{{uniqid().rand(0,9)}}" wire:model.defer="hashedPassword">
                    <option value=""></option>
                    @foreach (admins() as $name => $pwd)
                        <option value="{{ $pwd }}">{{ $name }}</option>
                    @endforeach
                </x-base-select>
            </div>
            <div>
                <x-base-input wire:model.defer="unhashedPassword" label="Contraseña" id="{{uniqid().rand(0,9)}}"
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
                console.log($('#openAuthorize'))
                $('#openAuthorize').first().click();
            })
        </script>
    @endpush
</div>
