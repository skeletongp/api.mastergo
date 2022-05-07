@push('js')
    <script>
        window.onbeforeunload = function(event) {
            if (@this.details.length > 0) {
                return @this.details
            };

        };
        let input = document.getElementById('outputScreen');

        function clr() {
            input.value = '';
            @this.set('form.cant', parseFloat(input.value));
        }

        // Del button 
        function del() {
            input.value = input.value.substring(0, input.value.length - 1);
            @this.set('form.cant', parseFloat(input.value));
        }
        // Making button works 
        function display(e, n) {
            e.target.blur()
            if (input.value == '0.000') {
                clr();
            }
            if (input.value.length > 11) {
                return;
            }
            if (n == '.' && input.value.includes('.')) {
                return;
            }
            if (n == '.' && input.value.length < 1) {
                n = '0.';
            }
            if (input.value == '0' && n !== '.') {
                input.value = '';
                input.value += n;
                return;
            }
            input.value += n;
            @this.set('form.cant', parseFloat(input.value));
        }
        // Enable Keyboard Input
        document.addEventListener("keydown", key, false);

        isFocused = false;

        function setFocused(val) {
            isFocused = val;
        }

        function keydownCant(e, num) {
            if (!isNaN(parseInt(num)) || num == '.') {
                display(e, num);
            } else if (num == 'Backspace') {
                del();
            } else if (num == 'Enter') {
                Livewire.emit('tryAddItems');
                search = "";
                @this.set('search', search);
                $('#searchImput').val('')
                setTimeout(() => {
                    clr();
                }, 500);
            } else if (num == 'Delete') {
                clr();
            } else if (num == 'F7' || num == 'AltGraph') {
                $('#btnmodalSale').click();
                open = !open;
                setTimeout(() => {
                    document.getElementById("prodsearch").focus();
                }, 0);

            } else if (num == 'F2') {
                Swal.fire({
                    title: 'Generar factura',
                    text: '¿Desea generar y enviar esta factura?',
                    confirmButtonText: 'Proceder',
                    confirmButtonColor: '#3895D3',
                    cancelButtonColor: '#F08080',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#sendInvoice').click();
                        search = "";
                        @this.set('search', search);
                        $('#searchImput').val('')
                    } else {
                        return;
                    }
                })
            }

        }
        urlParams = new URLSearchParams(window.location.search);
        search = urlParams.get('search')
        $('#searchImput').val(search)

        function keydownProduct(e, num) {

            abc = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'ñ', 'o', 'p', 'q', 'r', 's', 't',
                'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
            ]
            if (num == 'Backspace') {
                search = search.substring(0, search.length - 1)
            } else if (num == 'Delete') {
                search = "";
            } else if (abc.includes(num)) {
                search += num;
            } else if (num == 'Space') {
                search += " ";
            } else if (num == 'Enter') {
                @this.set('search', search)
            }
            if (search.length == 0) {
                search = search.replace('null', '');
                @this.set('search', search)
            }
            $('#searchImput').val(search)
        }

        function catchSearchEnter(event) {
            if (event.keyCode === 13) {
                @this.set('search', $('#searchImput').val())
            }
        }

        function key(e) {

            var keyCode = e.key || e.which;
            var num = keyCode;
            if (num == '%') {
                setFocused(true);
                document.getElementById('scanned').focus();
            }
            product = @this.producto;
            if (!isFocused && (product || num == 'F2')) {
                keydownCant(e, num)
            } else if (!isFocused && !product && e.target.id !== 'searchImput') {
                keydownProduct(e, num);
            }
        }
        $('#searchImput').on('search', function() {
            @this.set('search', $('#searchImput').val())
        });

        function facturar() {
            document.dispatchEvent(new KeyboardEvent('keydown', {
                'key': 'Enter'
            }));
            search = "";
            @this.set('search', search)
            $('#searchImput').val(search)
            console.log(search + '2')
        }

        function closeModal() {
            document.dispatchEvent(new KeyboardEvent('keydown', {
                'key': 'AltGraph'
            }));
        }

        function setFocusedOut(event) {
            @this.set('search', null);
            setFocused(false);
        }

        function kdwnPrice(e) {

            key = e.key || e.which;
            if (key == 'Tab') {
                return e.preventDefault();
            }
        }
    </script>
@endpush
