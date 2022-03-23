    <div class="absolute h-full top-3 w-56">
        <x-input label="Buscar" wire:model="search" class="relative w-56 max-w-xs">
            <x-slot name="icon">
                <span class="fas fa-search text-gray-500"></span>
            </x-slot>
        </x-input>
        <div class="bg-white z-50 px-2 shadow-xl text-sm">
            @if ($users->count() && $search)
                <ul class="py-4 space-y-2">
                    <li class="font-bold uppercase text-blue-200 ">Usuarios</li>
                    <hr>
                    @foreach ($users as $user)
                    <x-toast icon="fas fa-user text-blue-500" id="t{{ $user->id }}">
                        <x-slot name="text">
                            <a href="">{{ $user->name }}</a>
                        </x-slot>
                    </x-toast>
                    @endforeach
                </ul>
            @endif
            @if ($clients->count() && $search)
                <ul class="py-4 space-y-2 ">
                    <li class="font-bold uppercase text-blue-200 ">Clientes</li>
                    <hr>
                    @foreach ($clients as $client)
                        <x-toast icon="fas fa-user text-blue-500" id="t{{ $client->id }}">
                            <x-slot name="text">
                                <a href="">{{ $client->name }}</a>
                            </x-slot>
                        </x-toast>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
