var ClassMenu = (function() {
    
    var _self = this;
    
    this.cNombre = "";
    
    this.init = function init() {
        
    };
    
    this.initForma = function initForma(oParams) {
        
        $("#forma-menu").validate(oParams.paramsForma);
    };
    
    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombre = $("#cNombre").val();
        
        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }
        
        return false;
    };
});

ClassMenu.prototype = base;
var Menu = new ClassMenu();