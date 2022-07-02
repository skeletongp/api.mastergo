<div x-data="{
    edit: false,
    edited: false,
    init() {
        window.livewire.on('fieldEdited', (id) => {
            if (id === '{{ $rowId }}') {
                this.edited = true
                setTimeout(() => {
                    this.edited = false
                }, 5000)
            }
        })
    }
}" x-init="init()" :key="{{ $rowId }}">
    <button class="min-h-[28px] text-left w-full whitespace-nowrap hover:bg-blue-100 px-2 py-1  rounded focus:outline-none" x-bind:class="{ 'text-green-600': edited }" x-show="!edit"
        x-on:click="edit = true; $nextTick(() => { $refs.input.focus() })">{!! htmlspecialchars(is_numeric($value)?formatNumber($value):$value) !!}</button>
    <span x-cloak x-show="edit">
        <x-base-input label="" class="border-blue-400 px-2 py-1 w-max  rounded focus:outline-none  focus:border" x-ref="input" value="{!! htmlspecialchars($value) !!}" size="{{strlen($value)}}"
            wire:change="edited($event.target.value, '{{ $key }}', '{{ $column }}', '{{ $rowId }}')"
            x-on:click.away="edit = false" x-on:blur="edit = false" x-on:keydown.enter="edit = false" />
    </span>
</div>
