var ClassAccion = (function() {
    
    var _self = this;
    
    this.cNombre = "";
    this.cAlias = "";
    this.cDescripcion = "";
    this.cEtiquetaTitulo = "";
    this.cEtiquetaDescripcion = "";
    this.idTipoAccion = "";
    
    this.init = function init() {
        
    };
    
    this.initForma = function initForma(oParams) {
        
        $("#forma-acciones").validate(oParams.paramsForma);
    };
    
    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombre = $("#cNombreBus").val();
        this.cAlias = $("#cAliasBus").val();
        this.idTipoAccion = $("#idTipoAccionBus").val();
        
        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }
        
        return false;
    };
    
    this.btnRegresar = function btnRegresar(){
        window.location.href = Generic.BASE_URL + 'modulos/listado';
    }
});

ClassAccion.prototype = base;
var accion = new ClassAccion();