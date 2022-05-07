<div>
    <x-modal open="true" title="Se requiere autorización" fitVerticalContainer="true">
        <x-slot name="button">

        </x-slot>
        <form action="" wire:submit.prevent="authorizeAction" class="space-y-4">
            <div>
                <x-base-select label="Usuario" id="hashedPassword" wire:model.defer="hashedPassword">
                    @foreach ($this->admins as $name => $pwd)
                        <option value="{{ $pwd }}">{{ $name }}</option>
                    @endforeach
                </x-base-select>
            </div>
            <div>
                <x-base-input wire:model.defer="unhashedPassword" label="Contraseña" id="unhashedPassword"
                    type="password" autocomplete="off">
                </x-base-input>
            </div>
            <div class="flex justify-end">
                <x-button class="bg-gray-100 text-gray-800">
                    Autorizar
                </x-button>
            </div>
        </form>
    </x-modal>
</div>
