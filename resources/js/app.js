require("./bootstrap");

if (window.removeAccent == null) {
    window.removeAccent = function (string) {
        string = string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        return string;
    };
}
//detect if is mobile screen
if (window.isMobile == null) {
    window.isMobile = function () {
        return window.innerWidth <= 768;
    };
}

$(document).on('focus', 'input', function(e) {
   document.querySelector('input').scrollIntoView();
});