@props(['listName', 'inputId', 'label' => '', 'model' => null])
<div class="h-full">
    @if ($label)
        <label for="{{ $inputId }}"
            class="block text-base  font-medium text-gray-900 dark:text-gray-300">{{ $label }}</label>
    @endif
    <x-base-input label="" type="text" id="{{ $inputId }}" list="{{ $listName }}" onfocus="this.value=''"
        {{ $attributes }} />
    <datalist id="{{ $listName }}">
        {{ $slot }}
    </datalist>
    <span id="error" class="text-danger"></span>
    @push('js')
        <script>
            Livewire.on('clearSelect', () => {
                document.getElementById('{{ $inputId }}').value = '';
            });
            id = '{{ $inputId }}';
            input = $('#' + id);
                Livewire.hook('element.updated', (el, component) => {
                    id = '{{ $inputId }}';
                    input = $('#' + id);
                    list = '{{ $listName }}';
                    model = '{{ $model }}';
                    if (model && model!='product_name') {
                      
                        input.val(input.val());
                        return;
                    }
                })
            input.on('change', function() {
                value = $(this).val();
                list = '{{ $listName }}';
                model = '{{ $model }}';
                setValue = $('#' + list + ' [value="' + value.replace(/"/g, '&quot;') + '"]').data('value');
                if (model) {
                    @this.set(model, setValue);
                }
            })
            input.on('keypress', function(e) {
                id = '{{ $inputId }}';
                input = $('#' + id);
                if (e.keyCode == 13) {

                    list = '{{ $listName }}';
                    input.val($('#' + list).find('option:first').val());
                    input.trigger('change');
                    e.preventDefault();
                }
            })
        </script>
    @endpush
</div>
