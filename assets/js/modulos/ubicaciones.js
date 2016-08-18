/**
 * ClassUbicaciones
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de ubicaciones
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 22-03-2015
 */
var ClassUbicaciones = (function () {
    
    this.TEXT_ERROR_SELECCIONAR_COLONIA = "";
    this.TEXT_ERROR_SELECCIONAR_ESTADO = "";
    this.TEXT_ERROR_SELECCIONAR_CIUDAD = "";
    
    var _self = this;
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Publicos">
    
    
    /**
     * Metodo que se encarga de inicializar los parametros de la forma
     * 
     * @access public
     * @param objeto json Parametros necesarios para inicializar la forma
     *        -paramsForma parametros de configuracion del plugin validationEngine para la forma de modulo_singular_minuscula
     * @returns void no torna datos
     */
    this.initForma = function initForma(oParams) {
        $("#forma-ubicaciones").validate(oParams.paramsForma);
        $("#forma-ciudades").validate(oParams.paramsFormaCiudad);
        
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/getEstados",
            idElementoBase: "#idPais",
            idElementoActualizar: "#idEstado",
            valorDefault: "",
            agregarDefault: true,
        });
        
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/getCiudades",
            idElementoBase: "#idEstado",
            idElementoActualizar: "#idCiudad",
            valorDefault: "",
            agregarDefault: true,
        });
        
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/getColonias",
            idElementoBase: "#idCiudad",
            idElementoActualizar: "#idColonia",
            valorDefault: "",
            agregarDefault: true,
        });
        
        CoreUI.CatalogoSelectAjax({
            idSelectPadre: "idEstado",
            idSelectCatalogo: "idCiudad",
            idModal: "my-modal-ciudades",
            idHiddenSelectPadre: "idHiddenEstado",
            idButtonMostrar: "btnAgregarCiudades",
            idFormaCatalogo:"forma-ciudades",
            idButtonCatalogoCancel:"idButtonCatalogoCiudadCancel",
            idButtonCatalogoAceptar:"idButtonCatalogoCiudadAceptar",
            cController: "estados",
            mensajeErrorSeleccionarPadre: _self.TEXT_ERROR_SELECCIONAR_CIUDAD,
            urlGet:Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/getCiudades",
            urlInsert:Generic.BASE_URL + "ciudades/insertar",
            agregarDefault: true,
            valorDefault: "",
        });
    };
    
    
    // </editor-fold>
    
});

ClassUbicaciones.prototype = base;
var ubicaciones = new ClassUbicaciones();