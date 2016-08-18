/**
 * ClassBase
 * 
 * Clase que sirve como funciones genericas que pueden ser usadas en todos
 * las de mas clases de los diferentes modulos
 * 
 * @author DEVELOPER 1 email: <correo@developer1> cel: <1111111111>
 * @version 1.0
 * @created: 14/12/2013
 * @modified: 26/08/2014
 */
var ClassBase = (function() {

    var _selfBase = this;
    var _idPadre = null;

    this.TEXT_CERRAR_SESION = "";
    this.TEXT_BTN_ACEPTAR = "";
    this.TEXT_BTN_CANCELAR = "";
    
    this.TEXT_MENSAJE_ERROR_SELECCIONAR_REGISTRO = "";

    this.TIPO_MENSAJE_MSBOX = 'msgbox';
    this.TIPO_MENSAJE_GROWL = 'growl';
    this.TIPO_MENSAJE_LISTON = 'listado';

    this.TIPO_MENSAJE_ERROR = 'error';
    this.TIPO_MENSAJE_INFO = 'info';
    this.TIPO_MENSAJE_ALERT = 'alert';

    /////////////////////////////////////////////// funciones para acciones ////////////////////////////////////////////////////
    
    /**
     * Funcion que se encarga de redireccionar al usuario a la forma cuando no ha iniciado sesion
     * o bien se ha terminado el tiempo 
     * 
     * @access public
     * @returns {void}
     */
    this.irIniciarSesion = function irIniciarSesion(){
        window.location.href = Generic.BASE_URL + 'acceso/forma';
    };

    /**
     * Funcion generica que redirecciona al usuario a la forma de captura para agregar
     * un nuevo registro, recibe el idPadre en caso de ser un modulo anidado como por ejemplo
     * el catalogo de modulos es padre del catalogo de acciones, es usado para filtrar las acciones de acuerdo al modulo. 
     * 
     * @param {numeric|undefined} idPadre identificador del modulo padre en caso de existeir
     * @example http://workforce-cloud/modulo/forma  o  http://workforce-cloud/acciones/forma/1
     * @returns {void}
     */
    this.btnAgregar = function btnAgregar(idPadre) {
        var sUrl = Generic.BASE_URL + Generic.CONTROLLER + '/forma'
        if (typeof idPadre != "undefined" && idPadre > 0) {
            sUrl += "/" + idPadre;
        }
        window.location.href = sUrl;
    };

    /**
     * Funcion generica que se encarga de redireccionar a la pantalla del listado de un catalogo,
     * adicionalmente recibe como parametro el identigicador de el modulo padre en caso de existir
     * 
     * @acces public
     * @param {numeric|undefined} idPadre Identigicador del modulo padre en caso de existir
     * @returns void
     */
    this.btnListado = function btnListado(idPadre) {
        var sUrl = Generic.BASE_URL + Generic.CONTROLLER + '/listado'
        if (typeof idPadre != "undefined" && idPadre > 0) {
            sUrl += "/" + idPadre;
        }
        window.location.href = sUrl
    };

    /**
     * 
     * @param {numeric|undefined} idPadre Identigicador del modulo padre en caso de existir
     * @returns {undefined}
     */
    this.btnCancelar = function btnCancelar(idPadre) {
        this.btnListado(idPadre);
    };

    this.btnBusquedaAvanzada = function btnBusquedaAvanzada() {
        this.muestraOculta("busqueda-avanzada");
    };
    
    this.btnRecargar = function btnRecargar(){
        window.location.reload();
    }

    this.btnDashboard = function btnDashboard() {
        window.location.href = Generic.BASE_URL + "dashboard/index";
    };

    this.btnCancelarBusqueda = function btnCancelarBusqueda() {
        $("#forma-" + Generic.CONTROLLER + "-busqueda-avanzada").resetForm();
        this.btnBuscar();
    };

    this.btnGuardar = function btnGuardar(idPadre) {
        var bValidation = $("#forma-" + Generic.CONTROLLER).valid();
        if (bValidation) {
            if(typeof idPadre != "undefined"){
                _idPadre = idPadre;
            }
            var id = $("#forma-" + Generic.CONTROLLER).find("#id").val();
            var cUrl = ((typeof id !== "undefined") && (id != "")) ? Generic.BASE_URL + Generic.CONTROLLER + "/actualizar" : Generic.BASE_URL + Generic.CONTROLLER + "/insertar";
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: cUrl,
                success: _selfBase.btnGuardarSuccess
            };
            $("#forma-" + Generic.CONTROLLER).ajaxForm(oParams);
            $("#forma-" + Generic.CONTROLLER).submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };
    
    this.btnGuardarForma = function btnGuardarForma(){
        var bValidation = $("#forma-" + Generic.CONTROLLER).valid();
        if (bValidation) {
            if(typeof idPadre != "undefined"){
                _idPadre = idPadre;
            }
            var id = $("#forma-" + Generic.CONTROLLER).find("#id").val();
            var cUrl = (id != "") ? Generic.BASE_URL + Generic.CONTROLLER + "/actualizar" : Generic.BASE_URL + Generic.CONTROLLER + "/insertar";
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: cUrl,
                success: _selfBase.btnGuardarFormaSuccess
            };
            $("#forma-" + Generic.CONTROLLER).ajaxForm(oParams);
            $("#forma-" + Generic.CONTROLLER).submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };

    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        if (oResponse.success) {
            _selfBase.btnListado(_idPadre);
        }

        if (oResponse.failure) {
            _selfBase.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    };
    
    this.btnGuardarFormaSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        _selfBase.mostrarMensajesJSON(oResponse.data.messages);
    };

    this.btnCmd = function btnCmd(id, cUrl, oData, fnCallback) {
        if (id == "" || cUrl == "") 
        {
            return;
        }
        
        var aDataa = {"id": id};
        if(typeof oData !== "undefined")
        {
            aDataa = $.extend({}, aDataa, oData);
        }
        
        $.ajax({
            data: aDataa,
            url: cUrl,
            type: "POST",
            async: true,
            dataType:'json',
            success: function(oResponse) {
                if (oResponse.success) {
                    
                    if(typeof fnCallback !== "undefined")
                    {
                        fnCallback(oResponse);
                    }
                    else
                    {
                        oTable.fnDraw(false);
                        _selfBase.mostrarMensajesJSON(oResponse.data.messages);
                    }
                }

                if (oResponse.failure) {
                     _selfBase.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {
                _selfBase.mostrarErrorConexionServidor();
            }
        });
    };

    /////////////////////////////////////////////// funciones generales ////////////////////////////////////////////////////////

    this.muestraOculta = function muestraOculta(id) {
        $("#" + id).slideToggle('slow');
    };

    this.cerrarSession = function cerrarSession() {
        this.confirmarGeneral(this.TEXT_CERRAR_SESION, function() {
            var sUrl = Generic.$BASE_URL + 'acceso/cerrarSesion';
            window.location.href = sUrl;
        });
    };

    this.btnEliminar = function btnEliminar(id, cNombre, oData) {

        if(typeof id == "undefined" || typeof cNombre == "undefined"){
            CoreUI.mensajeMsgBox(Generic.TEXT_MENSAJE_ERROR_SELECCIONAR_REGISTRO, this.TIPO_MENSAJE_ERROR);
            return;
        }
        
        CoreUI.mensajeMsgBoxConfirm(CoreUtil.str.sprintf(Generic.TEXT_MENSAJE_ELIMINAR, [cNombre]), function(){
            _selfBase.btnCmd(id, Generic.BASE_URL + Generic.CONTROLLER + "/eliminar", oData);
        });
    };

    this.mostrarMensajesJSON = function mostrarMensajesJSON(oMessage) {
        if (typeof oMessage != "undefined") {
            CoreUI.addMessageJson(oMessage);
        }
    };

    /**
     * Funcion que se encarga de mostrar un mensaje de error por pantalla en donde se le notifica
     * al usuario de que existe errores en el formulario
     * 
     * @aces public
     * @returns void
     */
    this.mostrarMenajeErrorFormulario = function mostrarMenajeErrorFormulario() {

        CoreUI.mensajeMsgBox(Generic.TXT_MENSAHE_ERROR_FORMULARIO, this.TIPO_MENSAJE_ERROR);
    };

    /**
     * Funcion que se encarga de mostrar un mensaje de error al usuario en donde se le explica que
     * se ha producido un error de conexion al servidor
     * 
     * @acces public
     * @returns void
     */
    this.mostrarErrorConexionServidor = function mostrarErrorConexionServidor() {
        CoreUI.mensajeMsgBox(Generic.TEXT_MENSAJE_ERROR_CONEXION_SERVIDOR, this.TIPO_MENSAJE_ERROR);
    };
    
    
    this.numberFormat = function (input, iRedondear) {
        
        if(typeof iRedondear == "undefined"){
            iRedondear = 4;
        }

        var value = $(input).val();
        if (value > 0) {
            $(input).val(CoreUtil.format.formatNumber(value, "", iRedondear));
        } else {
            $(input).val(CoreUtil.format.formatNumber(0, "", iRedondear));
        }

    };
    
    
    this.borrarArchivoTemporal = function borrarArchivoTemporal(e,cNombreArchivo, fnCallbackSuccess)
    {
        e.preventDefault();
        $.ajax({
            data: {"cNombreArchivo":cNombreArchivo},
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/borrarArhivoTemporal",
            type: "POST",
            dataType:'json',
            success: function(oResponse) {
                
                if (oResponse.success) {
                    
                    $("#cNombreArchivo").val("");
                    $("#tabla-fileupload").empty();
                    
                    if(typeof fnCallbackSuccess === "function")
                    {
                        fnCallbackSuccess();
                    }
                }

                if (oResponse.failure) {
                     _selfBase.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {
                _selfBase.mostrarErrorConexionServidor();
            }
        });
    };
    
    /*********************** DESARROLLO CLIENTE ************************************************/
    
    /**
     * Metodo que se encarga de cambiar la configuracion del año y tipo de nomina de la barra de herramientas
     * @returns {undefined}
     */
    this.setTools= function setTools()
    {
        $.ajax({
            data: {
                "iAnoTools" : $("#iAnoTools").val(),
                "iTipoNominaTools" : $("#iTipoNominaTools").val()
            },
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/setTools/",
            type: "POST",
            async: true,
            dataType:'json',
            success: function(oResponse) {
                
                if (oResponse.success) {
                    
                }

                if (oResponse.failure) {
                     _selfBase.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {
                _selfBase.mostrarErrorConexionServidor();
            }
        });
    }
    
});

var base = new ClassBase();