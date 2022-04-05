<x-modal :open="!request()->session()->has('place_id') && auth()->user()->store->places->count()>1">
    <x-slot name="button"></x-slot>
    <x-slot name="title">Selecciona una sucursal</x-slot>
    <x-select wire:model="place_id">
        <option value=""></option>
        @foreach ($places as $id =>$place)
            <option value="{{$id}}">{{$place}}</option>
        @endforeach
    </x-select>
</x-modal>