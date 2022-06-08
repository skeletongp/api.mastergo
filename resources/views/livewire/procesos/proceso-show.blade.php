<div>
    <div class="flex space-x-4 p-8">
        <div class="w-full max-w-5xl mx-auto">
            <div class="flex justify-between items-center">
                <x-toggle id="status" wire:model='status' value='completed' label='{{ $statusTitle }}'></x-toggle>
                @livewire('productions.create-production', ['proceso' => $proceso], key($proceso->id))
            </div>
            <div class="">
                @if ($status)
                <livewire:productions.table-production :proceso="$proceso" :status="$status" />
                @else
                <livewire:productions.table-production :proceso="$proceso" :status="$status" />
                @endif
            </div>
        </div>
    </div>
</div>
