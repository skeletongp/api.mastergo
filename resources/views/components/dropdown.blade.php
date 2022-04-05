@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp

<div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="drop-trigger cursor-pointer overflow-hidden overflow-ellipsis whitespace-nowrap w-full select-none flex justify-between items-center">
        {{ $trigger }}
        <span class="fas fa-angle-down ml-4 transform" :class="open?' rotate-180' : 'rotate-0'" x-transition></span>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-400" x-cloak
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-400" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-md  {{ $alignmentClasses }}" 
        {{-- @click="open = false" --}}>
        <ul class="rounded-md shadow-xl  {{ $contentClasses }}">
            {{ $content }}
        </ul>
    </div>
</div>
