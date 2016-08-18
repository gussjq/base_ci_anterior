/**
 * ClassAvisos
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de avisos
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 22-03-2015
 */
var ClassAvisos = (function () {
    
    this.TIPO_BUSQUEDA_USUARIO = "";
    this.TIPO_BUSQUEDA_NIVEL_ACCESO = "";
    
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
        
        $("#cUsuario").bind("keydown", function (event) {
            if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            minLength: 3,
            dataType: 'json',
            source: function (request, response) {
                var aData = [];
                var iTipoUsuario = ($("#tipousuario_usuario").is(":checked"))
                        ? $("#tipousuario_usuario").val()
                        : $("#tipousuario_nivel_acceso").val();
                
                $.ajax({
                    url: Generic.BASE_URL + Generic.CONTROLLER + "/buscarUsuarios/",
                    dataType: "json",
                    type:"post",
                    async: false,
                    data: {
                        "term": _extractLast(request.term),
                        "iTipoUsuario": iTipoUsuario,
                        "aItems": _self.getSpanUsuarios()
                    },
                    success: function (oResponse) {
                        response($.map(oResponse.data, function (item) {
                                oResponse.iTipoUsuario = parseInt(oResponse.iTipoUsuario);
                                switch(oResponse.iTipoUsuario){
                                    case _self.TIPO_BUSQUEDA_USUARIO:
                                        return {ID: item.idUsuario, Name: item.cNombreCompleto + " &lsaquo;"+ item.cCorreo +"&rsaquo;"};
                                        break;
                                    case _self.TIPO_BUSQUEDA_NIVEL_ACCESO:
                                        return {ID: item.idRol, Name: item.cNombre };
                                        break;
                                }
                                    
                        }));
                    }
                });
            },
            focus: function () {
                // prevent value inserted on focus
                return false;
            },
            select: function (event, ui) {   
                $("#cUsuario").val("");
                var oListaUsuarios = $("#lista-usuarios");
                var oLi = $("<li id=\"li-identificador-"+ui.item.ID+"\" data-identificador=\""+ ui.item.ID +"\" ></li>").appendTo(oListaUsuarios);
                
                $("<span class=\"label label-info\">"+ ui.item.Name +" <a href=\"#\" class=\"color-blanco\" onclick=\"avisos.borrarUsuarioLista(event,"+ui.item.ID+")\"> x </a></span>").appendTo(oLi);
                
                return false;
            }
            
        }).data("autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                    .data("item.autocomplete", item)
                    .append("<a>" + item.Name + "</a>")
                    .appendTo(ul);
        };;
        
        tinymce.init({
            selector: 'textarea#txCuerpo',
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
                "advlist autolink link  lists charmap preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
                " table contextmenu directionality emoticons paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink  |  preview   | forecolor backcolor ",
        });
        
        $("#forma-avisos").validate(oParams.paramsForma);
    };
    
    /**
     * Metodo que se encarga de borrar un usuario de la lista 
     * @param {event} event Evento onclik
     * @param {int} idUsuario identificador del usuaro a borrar de la lista
     * @returns {undefined}
     */
    this.borrarUsuarioLista = function borrarUsuarioLista(event, identificador) {
        event.preventDefault();
        var oLi = $("#lista-usuarios").find("li#li-identificador-"+identificador);
        oLi.remove();
    };
    
    /**
     *Metodo que se encarga de generar un array con los ids del listado de usuarios 
     *@return {undefined}
     */
    this.getSpanUsuarios = function getSpanUsuarios(){
        var aItems = [];
        var identificador;
        var aLi = $("#lista-usuarios").find("li");
        $.each(aLi, function(key, value){
            identificador = $(value).attr("data-identificador");
            aItems.push(identificador);
        });
        return aItems;
    };
    
    this.setInputUsuarios = function setInputUsuarios(){
        var aLi = $("#lista-usuarios").find("li");
        var cListadoUsuarios = "";
        $.each(aLi, function(key, value){
            if(cListadoUsuarios != ""){
                cListadoUsuarios +=",";
            }
            identificador = $(value).attr("data-identificador");
            cListadoUsuarios += identificador;
        });
        $("#cListaUsuarios").val(cListadoUsuarios);
    };
    
    /**
     * Metodo para guardar el aviso
     * @returns {undefined}
     */
    this.btnGuardar = function btnGuardar() {
        this.setInputUsuarios();
        $("#txCuerpo").val(tinymce.get('txCuerpo').getContent());
        var bValidation = $("#forma-" + Generic.CONTROLLER).valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: Generic.BASE_URL + Generic.CONTROLLER + "/insertar",
                success: _self.btnGuardarSuccess,
            };
            $("#forma-" + Generic.CONTROLLER).ajaxForm(oParams);
            $("#forma-" + Generic.CONTROLLER).submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };
    
    /**
     * Metodo utilizado como respuesta
     */
    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        
        if (oResponse.success) {
            _self.btnCancelar();
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    };
    
    this.btnCancelar = function btnCancelar(){
        $("#txCuerpo").val("");
        $("#forma-" + Generic.CONTROLLER).clearForm();
        $("#lista-usuarios").empty();
        $("#tipousuario_usuario").attr("checked", "checked");
        tinymce.get('txCuerpo').setContent("");
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.limpiarContenedor = function limpiarContenedor(){
        $("#lista-usuarios").empty();
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.agregarItemTodos = function agregarItemTodos(){
        this.limpiarContenedor();
        var oListaUsuarios = $("#lista-usuarios");
        var oLi = $("<li id=\"li-identificador-1\" data-identificador=\"1\" ></li>").appendTo(oListaUsuarios);
                
        $("<span class=\"label label-info\">"+ this.TEXT_ETIQUETA_TODOS +"</span>").appendTo(oLi);
    };
    
    
    this.btnVisualizar = function btnVisualizar(idReceptor){
        
        $.ajax({
            url: Generic.BASE_URL +  "avisos/visualizar/",
            dataType: "json",
            data:{
                "idReceptor":idReceptor
            },
            type: "post",
            async: false,
            success: function (oResponse) {
                if (oResponse.success) {
                    _mostrarModalVisualizar(oResponse.data);
                }

                if (oResponse.failure) {
                     _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error:function(){
                _self.mostrarErrorConexionServidor();
            }
        });
    };
    
    // </editor-fold>
    
    function _split( val ) {
      return val.split( /,\s*/ );
    }
    
    function _extractLast( term ) {
      return _split( term ).pop();
    }
    
    function _mostrarModalVisualizar(data){
        var oTitulo = $("#myModal").find("#myModalLabel");
        var oCuerpo = $("#myModal").find("#txCuerpo");
        var oAviso = data.aviso;
        
        oTitulo.html(oAviso.cTitulo);
        oCuerpo.html(oAviso.txCuerpo);
        
        $("#myModal").modal("show");
        if(data.iNumeroAvisos > 0){
            $("#numero-avsos").html(data.iNumeroAvisos);
            $("#avisos-usuario").html(data.iNumeroAvisos);
        } else {
            $("#avisos-usuario").html("(0)");
            $("#numero-avsos").html("").removeClass("label label-info");
            $("#menu-numero-avisos").removeClass("label label-info");
        }
        
        if(Generic.CONTROLLER == "avisos"){
             oTable.fnDraw(true);
        }
       
    }
    
});

ClassAvisos.prototype = base;
var avisos = new ClassAvisos();