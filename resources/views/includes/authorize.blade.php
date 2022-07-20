<div>
    @php
        $authorizeIncluded=true;
    @endphp
    <x-modal zindex="2000"  hideButton="true">
        <x-slot name="button">
            <div id="openAuthorize"></div>
        </x-slot>
        <x-slot name="title">
            <span>Se requiere autorización</span>
        </x-slot>
        <form action="" wire:submit.prevent="authorizeAction('{{$action}}')" class="space-y-4 text-left">
            <span id="msgAuth" class="uppercase text-red-600 font-bold"></span>
            <div>
                <x-base-select label="Usuario" id="dsde{{uniqid().rand(0,9)}}" wire:model.defer="hashedPassword">
                    <option value=""></option>
                    @foreach (admins() as $name => $pwd)
                        <option value="{{ $pwd }}">{{ $name }}</option>
                    @endforeach
                </x-base-select>
            </div>
            <div>
                <x-base-input wire:model.defer="unhashedPassword" label="Contraseña" id="saas{{uniqid().rand(0,9)}}"
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
            Livewire.on('openAuthorize', function(msg){
                console.log(msg)
                $('#msgAuth').text(msg)
                $('#openAuthorize').first().click();
                return ;
            })
        </script>
    @endpush
</div>
