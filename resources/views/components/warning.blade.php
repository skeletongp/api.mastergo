@props(['idTT','msg'])
<div class="cursor-pointer  overflow-hidden">
    <x-tooltip id="{{$idTT}}">
      {{$msg}}
    </x-tooltip>
    <div data-tooltip-target="{{$idTT}}">
        {{$slot}}
    </div>
</div>