var ClassIdiomas = (function() {
    
    var _self = this;
    
    this.cNombre = "";
    this.cAlias = "";
    this.idIdioma = "";
    this.cNombreArchivo = "";
    
    
    this.init = function init() {
        
    };
    
    this.initForma = function initForma(oParams) {
        $("#forma-idiomas").validate(oParams.paramsForma);
    };
    
    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombre = $("#cNombre").val();
        this.cAlias = $("#cAlias").val();
        this.cNombreArchivo = $("#cNombreArchivo").val();
        
        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }
        
        return false;
    };
});

ClassIdiomas.prototype = base;
var idiomas = new ClassIdiomas();