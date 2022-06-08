<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('users.set-permissions', $user) }}
    @endslot

    <div class=" w-full mx-auto ">
        <div>
            <livewire:users.assign-permission :user="$user" :wire:key="uniqid().'id'" />
        </div>
    </div>

</x-app-layout>
