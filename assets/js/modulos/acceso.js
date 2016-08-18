/**
 * ClassAcceso
 * 
 * Clase que sirve para validar el acceso al sistema asi como tambien para el proceso
 * de restablecer contraseña
 * 
 * @author DEVELOPER 1 email: <correo@developer1> cel: <1111111111>
 * @created: 14/12/2013
 */
var ClassAcceso = (function () {

    var _self = this;

    // <editor-fold defaultstate="collapsed" desc="Metodos Publicos">

    /**
     * Metodo que se encarga de inicializar los parametros de la forma
     * 
     * @access public
     * @param objeto json Parametros necesarios para inicializar la forma
     *        -paramsForma parametros de configuracion del plugin validationEngine para la forma de acceso
     *        -paramsFormaRecuperar parametros de configuracion del plugin validationEngine para la forma de restablecer contrasena
     * @returns void no torna datos
     */
    this.initForma = function initForma(oParams) {
        $("#forma-acceso").validate(oParams.paramsForma);
        $("#forma-restablecer-contrasena").validate(oParams.paramsFormaRecuperar);
    };

    /**
     * Metodo que se encarga de inicializar los parametros de la forma de restablecer cotrasena
     * 
     * @access public
     * @param objeto json Parametros necesarios para inicializar la forma
     *        -paramsForma parametros de configuracion del plugin validationEngine para la forma de restablecer contrasena
     * @returns void
     */
    this.initFormaRestablecer = function initFormaRestablecer(oParams) {
        $("#forma-restablecer").validate(oParams.paramsForma);
    };

    /**
     * Metodo que se encarga de mostrar el efecto donde desaparese la forma de acceso y aparece la forma donde el usuario 
     * teclea su correo electronico y sele mandan los pasos para restablecer su contraseña
     * 
     * @access public
     * @returns void no retorna datos
     */
    this.mostrarRecuperar = function mostrarRecuperar() {
        $("#divAcceder").hide("slide", {}, 500, function () {
            $("#cCorreo").val("");
            $("#cContrasena").val("");
            $("#divRecuperar").show();
        });
    };

    /**
     * Metodo que se encarga de mostrar el efecto donde desaparese la forma de restablecer contraseña y aparece la forma de acceso
     * para ingresar al sistema
     * 
     * @access public
     * @returns void no retorna datos
     */
    this.mostrarAcceso = function mostrarAcceso() {
        $("#divRecuperar").hide("slide", {}, 500, function () {
            $("#cCorreo").val("");
            $("#divAcceder").show();
        });
    };

    /**
     * Metodo que se encarga de realizar la peticion ajax para la validacion de acceso
     * 
     * @access public
     * @returns void no retorna datos
     */
    this.btnAcceder = function btnAcceder() {
        var bValidation = $("#forma-acceso").valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: true,
                success: _accederSuccess
            };
            $("#forma-acceso").ajaxForm(oParams);
            $("#forma-acceso").submit();
        }
    };

    /**
     * Metodo que se encarga de realizar una peticion ajax para realizar la peticion de restablecer contraseña
     * en donde sele envia al usuario los pasos necesarios a seguir
     * 
     * @access public
     * @return {void} description void no retorna ningun valor
     */
    this.btnRestablecer = function btnRestablecer() {
        var bValidation = $("#forma-restablecer-contrasena").valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: true,
                success: _restablecerSuccess
            };
            $("#forma-restablecer-contrasena").ajaxForm(oParams);
            $("#forma-restablecer-contrasena").submit();
        }
    };

    /**
     * Metodo que se encarga de realizar una peticion ajax para actualizar la contraseña de usuario,
     * es usado para completar el proceso de restablecer contraseña
     * 
     * @access public
     * @returns {void} no retorna ningun valor
     */
    this.btnActualizarContrasena = function btnActualizarContrasena() {
        var bValidation = $("#forma-restablecer").valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: true,
                success: function (oResponse, sStatus, oXhr, oForm) {
                    if (oResponse.success) {
                        window.location.href = Generic.BASE_URL + oResponse.data.redirect;
                    }

                    if (oResponse.failure) {
                        _self.mostrarMensajesJSON(oResponse.data.messages);
                    }
                }
            };
            $("#forma-restablecer").ajaxForm(oParams);
            $("#forma-restablecer").submit();
        }
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">

    /**
     * Metodo privado que se encarga de realizar el proceso una vez que se a realizado la peticion ajax
     * para acceder al sistema
     * 
     * @access private
     * @param objet json oResponse tiene la respuesta del servidor
     * @param objeto sStatus estatus de la peticion
     * @param objeto oXhr tiene la respuesta del servidor
     * @param objeto oForm objeto del formulario que realiza la peticion
     * @returns {void}
     */
    function _accederSuccess(oResponse, sStatus, oXhr, oForm) {
        if (oResponse.success) {
            window.location.href = oResponse.data.redirect;
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }
    }

    /**
     * Metodo privado que se encarga de realizar el proceso una vez que se a realizado la peticion ajax
     * para restablecer la contraseña
     * 
     * @access private
     * @param objet json oResponse tiene la respuesta del servidor
     * @param objeto sStatus estatus de la peticion
     * @param objeto oXhr tiene la respuesta del servidor
     * @param objeto oForm objeto del formulario que realiza la peticion
     * @return {void}
     */
    function _restablecerSuccess(oResponse, sStatus, oXhr, oForm) {
        if (oResponse.success) {
            _self.mostrarAcceso();
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }
    }

    // </editor-fold>
});

ClassAcceso.prototype = base;
var acceso = new ClassAcceso();