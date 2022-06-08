/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
$(".confirm").on("click", function () {
  msg = $(this).attr("data-label");
  evento = $(this).attr("data-event");
  Swal.fire({
    title: "Aviso",
    text: msg,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Proceder",
    cancelButtonText: 'Cancelar'
  }).then(function (result) {
    if (result.isConfirmed) {
      Livewire.emit(evento);
    } else {
      return false;
    }
  });
});
/******/ })()
;