var ClassItems = (function() {

    var _self = this;

    this.idItems = "";
    this.idMenu = "";
    this.idAccion = "";
    this.idMenuPadre = "";
    this.iOrden = "";
    this.cLink = "";
    this.cEtiquetaTitulo = "";
    
    this.TEXT_NUEVO_ITEM = "";
    this.TEXT_EDITAR_ITEM = "";

    this.init = function init() {

    };

    this.initForma = function initForma(oParams) {

        $("#forma-tems").validate(oParams.paramsForma);
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getAccionesAjax",
            idElementoBase: "#idModulo",
            idElementoActualizar: "#idAccion",
            valorDefault: "",
            agregarDefault: true,
        });
        
        this.textNuevoItem();
        
         $("#cIconoUpload").uploadify({
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

                    $("#cIcono").val(oFileData.file_name);
                    $("#cImgUpload").attr("src", cPathTemporal);

                } else {

                    _self.mostrarMensajesJSON(_self.TIPO_MENSAJE_MSBOX, oResponse.data.messages);
                }
            }
        });
    };
    
    this.btnEditar = function btnEditar(id){
        if(typeof id == "undefined"){
            return;
        }
        
        this.textEditarItem();
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getItemAjax/",
            data: {
                "idItems": id
            },
            success: function(oResponse) {
                if (oResponse.success) {
                    var oItem = oResponse.data.item;
                    $("#id").val(oItem.idItems);
                    $("#idModulo").val(oItem.idModulo).change();
                    $("#cLink").val(oItem.cLink);
                    $("#cEtiquetaTitulo").val(oItem.cEtiquetaTitulo);
                    $("#cEtiquetaDescripcion").val(oItem.cEtiquetaDescripcion);
                    $("#idAccion").val(oItem.idAccion);
                    
                    if((typeof oItem.cIcono == "undefined")||(oItem.cIcono == null)){
                        $("#cImgUpload").attr("src", Generic.IMAGEN_DEFAULT);
                    } else {
                         if((typeof oItem.idModulo == "undefined")||(oItem.idModulo == null) || (oItem.idModulo == 0)){
                            $("#cImgUpload").attr("src", Generic.BASE_URL + Generic.DIRECTORIO_ITEMS_MENU + oItem.cIcono);
                        } else {
                            $("#cImgUpload").attr("src", Generic.BASE_URL + Generic.DIRECTORIO_MODULOS + oItem.cIcono);
                        }
                    }

                    setTimeout(function() {
                        $("#idAccion").val(oItem.idAccion);
                    }, 1500);
                }

                if (oResponse.failure) {
                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {

            }
        });
    };
    
    this.btnEliminar = function btnEliminar(id, cNombre){
        if(id > 0){
            CoreUI.mensajeMsgBoxConfirm(CoreUtil.str.sprintf(Generic.TEXT_MENSAJE_ELIMINAR, [cNombre]), function(){
                _self.eliminarItemAjax(id);
            });
            this.textNuevoItem();
        }
    };
    
    this.eliminarItemAjax = function eliminarItemAjax(id){
        this.textNuevoItem();
        $.ajax({
                type:"POST",
                dataType:"json",
                url:Generic.BASE_URL + Generic.CONTROLLER + "/eliminar/",
                data:{
                    "idItems":id
                },
                success:function(oResponse){
                    if (oResponse.success) {
                        $("ol.sortable").find("li#list_" + id).remove();
                        _self.mostrarMensajesJSON(oResponse.data.messages);
                    }

                    if (oResponse.failure) {
                        _self.mostrarMensajesJSON(oResponse.data.messages);
                    }

                    if (oResponse.noLogin) {
                        window.location.href = Generic.BASE_URL + Generic.MENU_LINK_DEFAULT;
                    }
                },
                error:function(){
                    
                }
            });
    };
    
    this.btnCancelar = function btnCancelar(){
        $("#forma-" + Generic.CONTROLLER).resetForm();
        $("#forma-" + Generic.CONTROLLER).find("#id").val("");
        
        this.textNuevoItem();
    };

    this.btnGuardar = function btnGuardar() {
        var bValidation = $("#forma-" + Generic.CONTROLLER).validate();
        if (bValidation) {
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
    }
    
    this.btnRegresar = function btnRegresar(){
        window.location.href = Generic.BASE_URL + "menu/listado";
    };

    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {

        if (oResponse.success) {
            var oItem = oResponse.data.item;
            var id = $("#forma-" + Generic.CONTROLLER).find("#id").val();
            
            if(id > 0){
                var oSpanTitulo = $("ol.sortable ").find("li#list_" + id).find("span.cTitulo")[0];
                $(oSpanTitulo).html(oItem.cTitulo);
            } else {
                _self.pintarItem(oItem);
            }
            
            _self.mostrarMensajesJSON(oResponse.data.messages);
            $("#forma-" + Generic.CONTROLLER).find("#id").val("");
            $("#forma-" + Generic.CONTROLLER).resetForm();
            
            _self.textNuevoItem();
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    };
    
    this.pintarItem = function pintarItem(item){
        
        var cHtml = "";
            cHtml +="<div class=\"cont_sortable\"><span class=\"disclose\"><span></span></span><span class\"cTitulo\">" + item.cTitulo + "</span>";
                cHtml += "<span class=\"icon_tools\">";
                    cHtml += "<a href=\"javascript:Items.btnEditar("+ item.idItem +");\" class=\"icono-editar\">";
                        cHtml += "<img src=\"" + Generic.BASE_URL + Generic.DIRECTORIO_ICON + "16_x_16/btn_edit.png \" />";
                    cHtml += "</a>";  

                    cHtml +="<a href=\"javascript:Items.btnEliminar("+ item.idItem +", '"+ item.cTitulo +"');\" class=\"icono-eliminar\">";
                        cHtml += "<img src=\"" + Generic.BASE_URL + Generic.DIRECTORIO_ICON + "16_x_16/cross_circle.png \" />";
                    cHtml += "</a>";
                cHtml += "</span>";   
            cHtml += "</div>";   
        
        var oListaSortable = $("ol.sortable");
        $("<li></li>").attr("id", "list_"+ item.idItem +"")
                    .attr("data-idItem", item.idItem)
                    .addClass("mjs-nestedSortable-branch mjs-nestedSortable-expanded")
                    .html(cHtml)
        .appendTo(oListaSortable);
    };

    this.ordenarMenu = function ordenarMenu(){
      
        var aOrdenItems = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
        var idMenu = $("#idMenu").val();
        
        if(typeof aOrdenItems != "undefined" && aOrdenItems.length > 0){
            $.ajax({
                type:"POST",
                dataType:"json",
                url:Generic.BASE_URL + Generic.CONTROLLER + "/ordenarMenuAjax/",
                data:{
                    "idMenu":idMenu,
                    "aItems":aOrdenItems
                },
                success:_self.ordenarMenuSuccess,
                error:_self.ordenarMenuError
            });
        }
    };
    
    this.ordenarMenuSuccess = function ordenarMenuSuccess(oResponse){
        
        if(oResponse.success){
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }
        
        if(oResponse.failure){
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }
        
        if(oResponse.noLogin){
            window.location.href = Generic.BASE_URL + Generic.MENU_LINK_DEFAULT;
        }
    };
    
    
    this.getLinkAjax = function getLinkAjax() {
        var idModulo = parseInt($("#idModulo").val());
        var idAccion = parseInt($("#idAccion").val());

        if ((isNaN(idModulo)) && (isNaN(idAccion))) {
            return;
        }
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getLinkAjax/",
            data: {
                "idModulo": idModulo,
                "idAccion": idAccion
            },
            success: function(oResponse) {
                if (oResponse.success) {
                    $("#cLink").val(oResponse.data.link);
                }

                if (oResponse.failure) {
                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {

            }
        });
    };
    
    this.textNuevoItem = function textNuevoItem(){
        $("#estado-catalogo").html(this.TEXT_NUEVO_ITEM);
        $("#cLink").val(Generic.MENU_LINK_DEFAULT);
        $("#cImgUpload").attr("src",Generic.IMAGEN_DEFAULT);
    };
    
    this.textEditarItem = function textEditarItem(){
        $("#estado-catalogo").html(this.TEXT_EDITAR_ITEM);
    };
});

ClassItems.prototype = base;
var Items = new ClassItems();


r = {"cNombreTabla":"tcdepartamentos", "iColumnas":2,"aColumnas":{"cCodigo":[{"type":"varchar(45)"}],"cNombre":[{"type":"varchar(45)"}]}};

