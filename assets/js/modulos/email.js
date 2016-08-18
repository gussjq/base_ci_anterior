var ClassEmail = (function() {

    var _self = this;

    this.cTitulo = "";
    this.idIdioma = "";
    this.idTipoEmail = "";
    this.cDescripcion = "";

    this.init = function init() {

    };

    this.initForma = function initForma(oParams) {

        tinymce.init({
            selector: 'textarea#txCuerpoTiny',
            convert_urls: false,
            browser_spellcheck: false,
            paste_data_images: true,
            editor_selector: "mceEditor",
            image_advtab: true,
            paste_as_text: true,
            language: 'es',
            theme: "modern",
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor ",
        });

        $("#forma-email").validate(oParams.paramsForma);
    };

    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cTitulo = $("#cTitulo").val();
        this.idIdioma = $("#idIdioma").val();
        this.idTipoEmail = $("#idTipoEmail").val();

        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }

        return false;
    };

    this.btnGuardar = function btnGuardar(idPadre) {
        var bValidation = $("#forma-" + Generic.CONTROLLER).validate();
        if (bValidation) {

            var txCuerpo = tinyMCE.get('txCuerpoTiny').getContent();
            $("#txCuerpo").val(txCuerpo);

            var id = $("#forma-" + Generic.CONTROLLER).find("#id").val();
            var cUrl = (id != "") ? Generic.BASE_URL + Generic.CONTROLLER + "/actualizar" : Generic.BASE_URL + Generic.CONTROLLER + "/insertar";
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: cUrl,
                success: _self.btnGuardarSuccess
            };
            $("#forma-" + Generic.CONTROLLER).ajaxForm(oParams);
            $("#forma-" + Generic.CONTROLLER).submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };
});

ClassEmail.prototype = base;
var email = new ClassEmail();