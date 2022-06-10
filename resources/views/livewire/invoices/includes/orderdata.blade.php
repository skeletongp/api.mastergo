<div class="">
   
    @push('js')
        <script>
            Livewire.on('printOrder', function(orden) {
               printOrder(orden)
            })

            function printOrder(order) {
                console.log(order)
                const ordConector = new ConectorPlugin();
                ordConector.texto(order.created_at)

                conector.cortar();
                conector.feed(3);
                conector.cortar();
                conector.imprimirEn(obj.place.preference.printer)
                    .then(respuestaAlImprimir => {
                        if (respuestaAlImprimir === true) {
                            console.log("Impreso correctamente");
                        } else {
                            console.log("Error. La respuesta es: " + respuestaAlImprimir);
                        }
                    });
            }
        </script>
    @endpush
</div>
