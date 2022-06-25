{{-- Requires: El elemento anexo requiere los valores data-tooltip-target="idDelTooltip"
                    data-tooltip-style="Tema" --}}
<div role="tooltip"
    {{ $attributes->merge(['class' => 'inline-block absolute invisible  py-2 px-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 tooltip']) }}
    style="z-index: 100">
    {{ $slot }}
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
