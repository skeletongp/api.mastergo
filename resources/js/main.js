$(".confirm").on("click", function () {
    msg = $(this).attr("data-label");
    Swal.fire({
        title: "Aviso",
        text: msg,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            event.stopImmediatePropagation();
        } else {
            return true;
        }
    });
});
