var ClassModulos = (function() {

    var _self = this;

    this.cNombreBus = "";
    this.cAliasBus = "";
    this.cDescripcionBus = "";
    this.cTituloEtiquetaBus = "";
    this.cDescripcionEtiquetaBus = "";
    this.bHabilitadoBus = "";

    this.TEXT_ERROR_CARGAR_ARCHIVO = "";

    this.init = function init() {
    };

    this.initForma = function initForma(oParams) {
        
        $("#forma-" +  Generic.CONTROLLER).validate(oParams.paramsForma);

        $("#cIconoUpload").uploadify({
            'uploader': Generic.BASE_URL + Generic.DIRECTORIO_JAVASCRIPT + 'plugins/uploadify/uploadify.swf',
            'script': Generic.BASE_URL + "modulos/cargarArchivoAjax",
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

                    $("#cIcono").val(oFileData.file_name);
                    $("#cImgUpload").attr("src", cPathTemporal);

                } else {

                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }
            }
        });
    };

    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombreBus = $("#cNombreBus").val();
        this.cAliasBus = $("#cAliasBus").val();

        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }

        return false;
    };
});

ClassModulos.prototype = base;
var modulos = new ClassModulos();