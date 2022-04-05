<div class=" w-full ">
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        Swal.fire({
            title: 'No tienes permiso para realizar esta acción',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href='/');
            }
        })
    </script>
</div>
