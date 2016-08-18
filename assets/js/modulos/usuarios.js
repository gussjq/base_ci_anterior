var ClassUsuario = (function() {

    var _self = this;

    this.cNombre = "";
    this.cApellidoPaterno = "";
    this.cApellidoMaterno = "";
    this.cCorreo = "";
    this.idRol = "";

    this.init = function init() {

    };

    this.initForma = function initForma(oParams) {
        $("#forma-usuarios").validate(oParams.paramsForma);

        $("#cImagenUpload").uploadify({
            'uploader': Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/uploadify/uploadify.swf',
            'script': Generic.BASE_URL + "usuarios/cargarArchivoAjax",
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

                    $("#cImagen").val(oFileData.file_name);
                    $("#cImgUpload").attr("src", cPathTemporal);

                } else {

                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }
            }
        });
    };

    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombre = $("#cNombreBus").val();
        this.cApellidoPaterno = $("#cApellidoPaternoBus").val();
        this.cApellidoMaterno = $("#cApellidoMaternoBus").val();
        this.cCorreo = $("#cCorreoBus").val();
        this.idRol = $("#idRolBus").val();

        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }

        return false;
    };

});

ClassUsuario.prototype = base;
var usuario = new ClassUsuario();