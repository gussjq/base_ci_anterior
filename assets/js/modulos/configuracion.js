var ClassConfiguracion = (function() {

    var _self = this;

    /**
     * Metodo encargado de inizializar los compoenentes necesarios para la forma
     * 
     * @acces public
     * @returns void
     */
    this.initForma = function initForma(oParams) {
        
        $("#forma-configuracion").validate(oParams.paramsForma);

        $("#cLogoUpload").uploadify({
            'uploader': Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/uploadify/uploadify.swf',
            'script': Generic.BASE_URL + "configuracion/cargarArchivoAjax",
            'cancelImg': Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/uploadify/cancel.png',
            'folder': Generic.BASE_URL,
            'buttonText': Generic.TEXT_SELECCIONAR,
            'auto': true,
            'multi': false,
            'fileDataName': 'cLogoUpload',
            'fileTypeExts': '*.gif; *.jpg; *.png',
            'onError': function(event, queueID, fileObj, errorObj) {
                CoreUI.mensajeMsgBox(Generic.TXT_ERROR_SUBIR_ARCHIVO, _self.TIPO_MENSAJE_ERROR);
            },
            'onComplete': function(event, queueID, fileObj, response, data) {
                var oResponse = $.parseJSON(response);
                if (oResponse.success == true) {
                    var oFileData = oResponse.data.aFileData;
                    var cPathTemporal = Generic.BASE_URL + Generic.DIRECTORIO_TEMPORAL + oFileData.file_name;
                    
                    $("#cLogo").val(oFileData.file_name);
                    $("#cImgUpload").attr("src", cPathTemporal);
                    
                } else {
                    
                     _self.mostrarMensajesJSON(_self.TIPO_MENSAJE_MSBOX, oResponse.data.messages);
                }
            }
        });
    };

    this.btnGuardar = function btnGuardar() {
        var bValidation = $("#forma-configuracion").valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: false,
                success: _self.btnGuardarSuccess
            };
            $("#forma-configuracion").ajaxForm(oParams);
            $("#forma-configuracion").submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };

    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        _self.mostrarMensajesJSON(oResponse.data.messages);
    };
    
    this.btnRegresar = function btnRegresar(){
        window.location.href = Generic.BASE_URL + Generic.MENU_LINK_DEFAULT;
    };

});

ClassConfiguracion.prototype = base;
var configuracion = new ClassConfiguracion();