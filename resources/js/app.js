require("./bootstrap");

if(window.removeAccent==null){
  window.removeAccent = function (string) {
    string = string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    return string;
};  
}