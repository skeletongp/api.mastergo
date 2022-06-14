<div>
    @can('Crear Cuentas')
        <x-modal open :fitV="false" maxHeight="max-h-[70vh]" title="Nueva Cuenta" maxWidth="max-w-3xl">
            <x-slot name="button">
                <span>
                    Añadir Cuenta
                </span>
            </x-slot>
            <form action="" wire:submit.prevent="createCount">
                <div class="space-y-4">
                    <div class="flex space-x-4 items-start">
                        <div class="w-full">
                            <x-base-select label="Cuenta Control" id="ctaControl" wire:model="code">
                                <option value=""></option>
                                @foreach ($counts as $code => $cta)
                                    <option value="{{ $code }}">{{ $cta }}</option>
                                @endforeach
                                </x-select>
                                <x-input-error for="code"></x-input-error>
                        </div>
                        <div class="w-full">
                            @if ($names)
                                <x-base-select label="Cuenta Control" id="ctaName" wire:model="name">
                                    @foreach ($names as $nm)
                                        <option>{{ $nm }}</option>
                                    @endforeach
                                </x-base-select>
                            @else
                                <x-base-input label="Nombre de la cuenta" id="ctaName"
                                    placeholder="Ingrese el nombre de la cuenta" wire:model.defer="name">

                                </x-base-input>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-full">
                            <x-base-select label="Titular de la cuenta" id="ctaTitular" wire:model="model_id">
                                <option value="">General</option>

                                @foreach ($instances as $index => $fullname)
                                    <option value="{{ $index }}">{{ $fullname }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="model_id"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-base-select label="Origen de la cuenta" id="ctaOrigin" wire:model="origin">
                                <option value=""></option>
                                @foreach (App\Models\Count::ORIGINS as $ind => $orName)
                                    <option value="{{ $ind }}">{{ $orName }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="origin"></x-input-error>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-button>
                            Crear Cuenta
                        </x-button>
                    </div>
                </div>
            </form>
        </x-modal>
    @endcan
</div>
