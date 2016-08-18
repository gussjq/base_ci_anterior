/**
 * ClassPerfil
 * 
 * Clase que sirve para administrar las preferencias de usuario
 * 
 * @author DEVELOPER 1 email: <correo@developer1> cel: <1111111111>
 * @created: 26/01/2015
 */
var ClassPerfil = (function() {
    
    var _self = this;
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Publicos">
    
    
    /**
     * Metodo que se encarga de inicializar el formulario
     * @access public
     * @returns {undefined} No retorna valor
     */
    this.initForma = function initForma(oParams) {
       
        _mostrarMensajeNuevoUsuario();
        $("#forma-perfil").validate(oParams.paramsForma);
        
        
    };
    
    this.btnGuardarForma = function btnGuardarForma(){
        var bValidation = $("#forma-perfil").valid();
        if (bValidation) {
            var id = $("#forma-perfil").find("#id").val();
            var cUrl = (id != "") ? Generic.BASE_URL + Generic.CONTROLLER + "/actualizar" : Generic.BASE_URL + Generic.CONTROLLER + "/insertar";
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: cUrl,
                success: _self.btnGuardarFormaSuccess
            };
            $("#forma-perfil").ajaxForm(oParams);
            $("#forma-perfil").submit();
        } else {
            _self.mostrarMenajeErrorFormulario();
        }
    };
    
    /**
     * 
     * @param {type} oResponse
     * @param {type} sStatus
     * @param {type} oXhr
     * @param {type} oForm
     * @returns {undefined}
     */
    this.btnGuardarFormaSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        if (oResponse.success) {
            window.location.reload();
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
        
    };
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    
    /**
     * Metodo que se encarga de validar si es un usuario nuevo que requiere de modificar su contraseña,
     * muestra un mensaje de modal con las instrucciones necesarias para modificar su contraseña
     * 
     * @access private
     * @returns {undefined} No retorna valor
     */
    function _mostrarMensajeNuevoUsuario(){
        var iNuevoUsuario = $("#bNuevo").val();
        iNuevoUsuario = parseInt(iNuevoUsuario);
        
        if(iNuevoUsuario === 1){
            CoreUI.mensajeMsgBox(Generic.TEXT_MENSAJE_NUEVO_USUARIO, _self.TIPO_MENSAJE_INFO);
        }
    }
    
    this.validarContrasenaUsuarioNuevo = function validarContrasenaUsuarioNuevo(){
        var iNuevoUsuario = $("#bNuevo").val();
        iNuevoUsuario = parseInt(iNuevoUsuario);
        
        return (iNuevoUsuario === 1);
    }
    
    // </editor-fold>
});

ClassPerfil.prototype = base;
var perfil = new ClassPerfil();