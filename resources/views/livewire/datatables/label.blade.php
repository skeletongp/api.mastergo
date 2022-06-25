
<div class="table-cell  {{isset($padding)?$padding:'py-4 px-6 '}}  whitespace-no-wrap @if($column['headerAlign'] === 'right') text-right @elseif($column['headerAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }}">
    {!! $column['content'] ?? '' !!}
</div>
