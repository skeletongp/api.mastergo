@props(['listName', 'inputId', 'label' => '', 'model' => null])
<div class="">
    @if ($label)
        <label for="{{ $inputId }}"
            class="block text-base pb-2 font-medium text-gray-900 dark:text-gray-300">{{ $label }}</label>
    @endif
    <input type="search" id="{{ $inputId }}" list="{{ $listName }}"
        {{ $attributes->merge([
            'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 
                    focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        ]) }}>
    <datalist id="{{ $listName }}">
        {{ $slot }}
    </datalist>
    <span id="error" class="text-danger"></span>
    @push('js')
        <script>
            id = '{{ $inputId }}';

            input = $('#' + id);
            input.on('change', function() {
                value = $(this).val();
                list = '{{ $listName }}';
                model = '{{ $model }}';
                setValue = $('#' + list + ' [value="' + value + '"]').data('value');
                if (model) {
                    @this.set(model, setValue);
                }
            })
        </script>
    @endpush
</div>
