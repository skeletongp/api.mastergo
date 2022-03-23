@props(['type' => 'success', 'message'])

@php
switch ($type) {
    case 'success':
        $color = 'bg-green-50 hover:bg-green-100';
        $icon = 'fas fa-check text-2xl text-green-700';
        break;
    case 'error':
        $color = 'bg-red-50 hover:bg-red-100';
        $icon = 'fas fa-times  text-2xl text-red-700';
        break;
    case 'warning':
        $color = 'bg-yellow-50 hover:bg-yellow-100';
        $icon = 'fas fa-circle-info text-2xl text-yellow-700';
        break;
}
@endphp
<div x-data="{open: true}" class="absolute">
    <div x-show="open" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="opacity-100 -translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 -translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full"
        class="flex justify-between w-max items-center space-x-4 cursor-default select-none px-2.5 py-2.5 text-lg text-gray-700 shadow-sm rounded-xl {{ $color }}">
        <span class="{{ $icon }} font-bold"></span>
        <span>{{ $message }}</span>
        <span class="fas fa-times text-red-600 cursor-pointer" @click="open = false"></span>
    </div>
</div>
