var ClassCoreUI = (function () {

    var _CoreUI = this;

    var _EXITO_MENSAJE = 'exito_mensaje';
    var _ERROR_MENSAJE = 'error_mensaje';
    var _ALERTA_MENSAJE = 'alerta_mensaje';

    var _TIPO_MENSAJE_MSBOX = 'msgbox';
    var _TIPO_MENSAJE_GROWL = 'growl';    

    this.TIPO_MENSAJE_ERROR = 'error';
    this.TIPO_MENSAJE_INFO = 'info';
    this.TIPO_MENSAJE_ALERT = 'alert';


    this.selectMenu = function selectMenu(cNombre) {

        if (typeof cNombre == "undefined" || cNombre == null || cNombre == "") {
            return;
        }

        cNombre = cNombre.toLowerCase();

        var oMainNav = $("#main-nav");
        var oItemsPadre = oMainNav.find("li[data-menu-padre]");
        var oItemsMenu = oMainNav.find("ul").find("li[data-nombre-item]");

        oItemsPadre.removeClass("active");
        if (cNombre == "dashboard") {
            var oDashboard = oMainNav.find(".dashboard");
            oDashboard.addClass("active");
            return;
        }

        var oItem = null;
        var cNombreModulo = "";
        var oItemPadre = null;
        $.each(oItemsMenu, function (iKey, item) {
            oItem = $(item);
            cNombreModulo = oItem.attr("data-nombre-item");
            cNombreModulo = cNombreModulo.toLowerCase();
            if (cNombreModulo == cNombre) {
                oItemPadre = _CoreUI.retornaPadreMenu(oItem);
                oItemPadre.addClass("active");
            }
        });
    };

    this.retornaPadreMenu = function retornaPadreMenu(oItem) {
        var oPadreMenu = oItem.parent("li[data-menu-padre]");
        if (typeof oPadreMenu == "undefined" || oPadreMenu.length == 0) {
            var oPadre = oItem.parent();
            oPadreMenu = _CoreUI.retornaPadreMenu(oPadre);
        }
        return oPadreMenu;
    };


    this.Menu = function Menu(cRuta) {
        window.location.href = cRuta;
    };
    
    /**
     * Metodo que retorna
     */
    this.tablaFileUpload = function tablaFileUpload(oFile)
    {
        var tabla = "<table role=\"presentation\" class=\"table table-striped\">";
            tabla += "<tbody class=\"files\">";
        
                tabla += "<tr class=\"template-upload fade in\">";
                    tabla += "<td>";
                        tabla += "<p class=\"name\">"+ oFile.orig_name +"</p>";
                    tabla += "</td>";
                    
                    tabla += "<td>";
                        tabla += "<p class=\"size\">"+ oFile.file_size +" KB</p>";
                        
                        tabla += "<div id=\"progress\" class=\"progress\">";
                            tabla += "<div class=\"progress-bar progress-bar-success\" style=\"width: 100%;\"></div>";
                        tabla += "</div>";
                    tabla += "</td>";
                    
                    tabla += "<td>";
                        tabla += "<button class=\"btn btn-danger cancel\" onclick=\"base.borrarArchivoTemporal(event,'"+ oFile.file_name +"');\">";
                            tabla += "<i class=\"icon-delete\"></i>";
                            tabla += "<span>"+ Generic.TEXT_CANCELAR  +"</span>";
                        tabla += "</button>";
                    tabla += "</td>";
                tabla += "</tr>";
        
            tabla += "</tbody>";
        tabla += "</table>";
        
        return tabla;
    };

    /**
     * Funcion encargada de solucionar bug de posicion de mensajes de error validation engine
     *  
     * @acces public
     * @returns void
     * 
     * @actualizado 14-01-2015 Se modifico esta fucion debido a que el plugin validate en su metodo updatePromptsPosition
     * causa conflicto con el css de boostrap, estaba anterior mente de esta manera pq en la base pasada se usaba jQuery UI para los tabs
     */
//    this.updatePromptsPositionTabs = function updatePromptsPositionTabs() {
//        $('.ui-tabs-anchor').click(function(e) {
//            var href = $(this).attr('href');
//            $(href).find('.formError').hide();
//            $(href).find('.formError').validationEngine('updatePromptsPosition');
//            $(href).find('.formError').show('fade');
//        });
//    }

    this.updatePromptsPositionTabs = function updatePromptsPositionTabs() {
        $('.nav-tabs').find("li").find("a").click(function (e) {
            cId = $(this).attr("href");
            oTab = $(".tab-content").find("" + cId)

            oTab.find('.formError').hide();
            // oTab.find('.formError').validationEngine('updatePromptsPosition'); 
            oTab.find('.formError').show('fade');
        });
    }

    this.addMessageJson = function addMessageJson(oJson) {
        var cMensaje = "";
        var cTipo = "";
        for (var iIndex in oJson.lTipos) {
            switch (oJson.lTipos[iIndex]) {
                case _EXITO_MENSAJE:
                    var aMensajes = oJson.lMensajes[iIndex];
                    if (aMensajes.length > 0) {
                        for (var iIndex in aMensajes) {
                            cMensaje = aMensajes[iIndex] + "\n";
                            this.mensajeGrowl(cMensaje, "success", Generic.TEXT_TITULO_EXITO);
                        }
                    }
                    break;
                case _ALERTA_MENSAJE:
                    cMensaje = this.convertArrayToHtml(oJson.lMensajes[iIndex]);
                    this.mensajeMsgBox(cMensaje, "alert");
                    break;
                case _ERROR_MENSAJE:
                    cMensaje = this.convertArrayToHtml(oJson.lMensajes[iIndex]);
                    this.mensajeMsgBox(cMensaje, "error");
                    break;
            }
        }
    };

    this.mensajeMsgBox = function mensajeMsgBox(cMensaje, cTipo) {
        $.msgbox(cMensaje, {
            type: cTipo,
            buttons: [{
                    type: "cancel",
                    value: Generic.TEXT_ACCION_ACEPTAR
                }]
        });
    };

    this.mensajeMsgBoxConfirm = function mensajeMsgBoxConfirm(cMensaje, fnAceptar) {
        $.msgbox(cMensaje, {
            type: "confirm",
            buttons: [{
                    type: "submit",
                    value: Generic.TEXT_ACCION_ACEPTAR
                }, {
                    type: "cancel",
                    value: Generic.TEXT_ACCION_CANCELAR
                }]
        }, function (result) {
            if (result) {
                if (typeof fnAceptar == "function") {
                    fnAceptar();
                }
            }
        });
    };

    this.mensajeGrowl = function mensajeGrowl(cMensaje, cTipo, cTitulo) {
        $.msgGrowl({
            type: cTipo,
            title: cTitulo,
            text: cMensaje,
            position: 'top-right'
        });
    };

    this.convertArrayToString = function convertArrayToString(aMensajes) {
        var cMensajes = "";
        if (aMensajes.length > 0) {
            for (var iIndex in aMensajes) {
                cMensajes = cMensajes + aMensajes[iIndex] + "\n";
            }
        }
        return cMensajes;
    };

    this.convertArrayToHtml = function convertArrayToHtml(aMensajes) {
        var cMensajes = "";
        if (aMensajes.length > 0) {
            for (var iIndex in aMensajes) {
                cMensajes = "<div>" + cMensajes + aMensajes[iIndex] + "</div>";
            }
        }
        return cMensajes;
    };

    this.mostrarLoadingAJAX = function mostrarLoadingAJAX() {
        $(document).ajaxStart(function () {
            $("#overley-loading").show();
            $("#content-img-loading").show();
        });

        $(document).ajaxStop(function () {
            $("#overley-loading").hide();
            $("#content-img-loading").hide();
        });
    };

    this.mostrarMessageFlash = function mostrarMessageFlash(oMessage) {
        if ((typeof oMessage != "undefined") && (typeof oMessage != "null") && (oMessage != "null")) {
            this.addMessageJson(oMessage);
        }
    };
    
    
    this.limpiarForma = function limpiarForma(cForma) 
    {
        var oForma = $("#" + cForma);
        
        if(typeof oForma === "undefined")
        {
            return;
        }
        
        var aInputs = oForma.find("input");
        
        console.log(aInputs);
    };
});

ClassCoreUI.prototype.CacheDataTable = function (options) {
    options = $.extend({}, this.defaults, options);
    this.timeout = null;
    this.delay = options.delay;
    this.async = options.async;
    this.oCache = {
        newRequest: null,
        fnCallback: null,
        sSource: null,
        bNewRequest: null,
        bServerBusy: false
    };

    this.arraySetKey = function arraySetKey(aoData, sKey, mValue) {
        for (var i = 0, iLen = aoData.length; i < iLen; i++) {
            if (aoData[i].name == sKey) {
                aoData[i].value = mValue;
            }
        }
    };

    this.arrayGetKey = function arrayGetKey(aoData, sKey) {
        for (var i = 0, iLen = aoData.length; i < iLen; i++) {
            if (aoData[i].name == sKey) {
                return aoData[i].value;
            }
        }
        return null;
    };

    var objCache = this;
    this.requestPipeLine = function requestPipeLine() {
        clearTimeout(this.timeout);
        if (!objCache.oCache.bServerBusy) {
            objCache.oCache.bNewRequest = false;
        }

        var aoData = objCache.oCache.newRequest;
        var sEcho = objCache.arrayGetKey(aoData, "sEcho");
        var iRequestStart = objCache.arrayGetKey(aoData, "iDisplayStart");
        var iRequestLength = objCache.arrayGetKey(aoData, "iDisplayLength");
        var sSearch = objCache.arrayGetKey(aoData, "sSearch");
        var iRequestEnd = iRequestStart + iRequestLength;

        if (!objCache.oCache.bServerBusy) {
            objCache.oCache.bServerBusy = true;
            $.ajax({
                "mode": "abort",
                "dataType": "json",
                "type": "POST",
                "url": objCache.oCache.sSource,
                "data": aoData,
                "async": objCache.async,
                "success": function (json) {

                    objCache.oCache.bServerBusy = false;
                    if (objCache.oCache.bNewRequest) {
                        objCache.requestPipeLine();
                    } else {
                        if (options.complete !== undefined && typeof (options.complete) == "function") {
                            options.complete(json);
                        }
                        objCache.oCache.fnCallback(json);
                        if (json.sMessage) {
                            alert(json.sMessage);
                        }
                    }
                },
                error: function (request, status, error) {
                    if (options.onerror !== undefined && typeof (options.onerror) == "function") {
                        options.onerror(request, status, error);
                    } else {
                        
                    }
                }
            });
        }
    };

    this.tablePipeLine = function tablePipeLine(sSource, aoData, fnCallback) {
        this.oCache.newRequest = aoData;
        this.oCache.fnCallback = fnCallback;
        this.oCache.sSource = sSource;
        this.oCache.bNewRequest = true;

        if (this.timeout != null) {
            clearTimeout(this.timeout);
        }
        this.timeout = setTimeout(this.requestPipeLine, this.delay);
    };
};

ClassCoreUI.prototype.CacheDataTable.defaults = {
    delay: 1000,
    async: true
};

ClassCoreUI.prototype.MultiSelectAjax = function (options) {
    this.defaults = {
        agregarDefault: false,
        EtiquetaSeleccione: Generic.TEXT_SELECCIONAR,
        EtiquetaCargando: Generic.TEXT_CARGANDO,
        async: true,
        sendEmptyValues: false,
        valorDefault: 0,
        cOverrideKey: false
    };
    var objMgr = this;
    options = $.extend({}, objMgr.defaults, options);

    function beforeSend(options) {
        $(options.idElementoActualizar).attr('disabled', true).find('option').remove();
        $(options.idElementoActualizar).append('<option value="">' + options.EtiquetaCargando + '</option>');
        if (options.beforeSend != undefined) {
            options.beforeSend(options);
        }
    }

    function complete(options, json) {
        $(options.idElementoActualizar).find('option').remove();
        if (options.agregarDefault) {
            var newOption = '<option value="' + options.valorDefault + '">' + options.EtiquetaSeleccione + '</option>';
            $(options.idElementoActualizar).append(newOption);
        }
        if (typeof json.data != "undefined") {
            for (i = 0; i < json.data.options.length; i++) {
                json.data.options[i].Text = CoreUtil.html.html_entity_decode(json.data.options[i].Text);
                var newOption = '<option value="' + json.data.options[i].ID + '">' + json.data.options[i].Text + '</option>';
                $(options.idElementoActualizar).append(newOption);
            }
        }
        $(options.idElementoActualizar).removeAttr("disabled");
        if (options.complete) {
            options.complete(options, json);
        }
    }

    function getSelectedIndexes(options) {
        var selItems = $(options.idElementoBase).find("option:selected");
        var sendIndex = null;
        if (selItems.length > 1) {
            sendIndex = new Array();
            for (i = 0; i < selItems.length; i++) {
                sendIndex[i] = $(selItems[i]).attr("value");
            }
        } else if (selItems.length == 1) {
            sendIndex = $(selItems[0]).attr("value");
        }

        return sendIndex;
    }

    this.beforeSend = beforeSend;
    this.complete = complete;
    this.getSelectedIndexes = getSelectedIndexes;

    $(options.idElementoBase).change(function () {
        if (!options.sendEmptyValues) {
            if ($(options.idElementoBase).find("option:selected").length == 1 && $(options.idElementoBase).val() == options.valorDefault) {
                objMgr.complete(options, {options: []});
                return;
            }
        }
        objMgr.beforeSend(options);

        var defParamasAjax = {
            dataType: 'json',
            type: "POST",
            url: options.url,
            async: options.async,
            success: function (json) {
                if (options.formatData !== undefined && typeof (options.formatData) == "function") {
                    json = options.formatData(options, json);
                }
                objMgr.complete(options, json);
            },
            error: function (request, status, error) {
                console.log(status);
            }
        };
        var paramsAjax = {};
        if (options.configureAjax) {
            paramsAjax = options.configureAjax(options);
        }
        var finalParams = $.extend(defParamasAjax, paramsAjax);
        if (options.cOverrideKey != false) {
            if (finalParams.data == undefined) {
                finalParams.data = {};
                finalParams.data[options.cOverrideKey] = objMgr.getSelectedIndexes(options);
            }
        } else {
            if (finalParams.data == undefined) {
                var idCtrl = (options.nombreEnviarBase == undefined) ? $(options.idElementoBase).attr("id") : options.nombreEnviarBase;
                finalParams.data = {};
                finalParams.data[idCtrl] = objMgr.getSelectedIndexes(options);
            }
        }

        $.ajax(finalParams);
    });

};


ClassCoreUI.prototype.CatalogoSelectAjax = function (options) {

    this.defaults = {
        bPadre: true,
        idSelectPadre: "",
        idSelectCatalogo: "",
        idModal: "",
        idHiddenSelectPadre: "",
        idFormaCatalogo: "",
        idButtonMostrar: "",
        idButtonCatalogoCancel: "idButtonCatalogoCancel",
        idButtonCatalogoAceptar: "idButtonCatalogoAceptar",
        mensajeErrorSeleccionarPadre: "",
        fnMostrarCatalogo: false,
        fnBtnGuardarModalCatalogo: false,
        fnbtnCerrarModalCatalogo: false,
        fnComplete: false,
        cController: "",
        urlGet: "",
        urlInsert: "",
        agregarDefault: false,
        EtiquetaSeleccione: Generic.TEXT_SELECCIONAR,
        EtiquetaCargando: Generic.TEXT_CARGANDO,
        async: true,
        sendEmptyValues: false,
        valorDefault: 0,
        configureAjax: false,
        cOverrideKey: false,
        cOverridePadreKey: false
    };

    var objMgr = this;
    options = $.extend({}, objMgr.defaults, options);

    function _fnMostrarCatalogo(options) {
        if (options.bPadre) {
            var identificador = $("#" + options.idSelectPadre).val();
            $("#" + options.idHiddenSelectPadre).val(identificador);

            if (identificador > 0) {
                $('#' + options.idModal).modal('show');
            } else {
                CoreUI.mensajeMsgBox(options.mensajeErrorSeleccionarPadre, CoreUI.TIPO_MENSAJE_ERROR);
            }
        } else {
            $('#' + options.idModal).modal('show');
        }
    }
    ;

    // metodos privados del plugin

    function _fnBtnGuardarModalCatalogo(options) {
        var bValidation = $("#" + options.idFormaCatalogo).valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: options.urlInsert,
                success: function (oResponse, sStatus, oXhr, oForm) {
                    if (oResponse.success) {
                        _fnbtnCerrarModalCatalogo(options);
                        _fnCargarSelectCatalogo(oResponse.data, options);
                    }

                    if (oResponse.failure) {
                        base.mostrarMensajesJSON(oResponse.data.messages);
                    }

                    if (oResponse.noLogin) {
                        window.location.href = Generic.BASE_URL + 'dashboard/index';
                    }
                }
            };
            $("#" + options.idFormaCatalogo).ajaxForm(oParams);
            $("#" + options.idFormaCatalogo).submit();
        } else {
            base.mostrarMenajeErrorFormulario();
        }
    }
    ;

    function _fnbtnCerrarModalCatalogo(options) {
        var oForma = $("#" + options.idFormaCatalogo);
        oForma.find(":input").val("");
        $('#' + options.idModal).modal('hide');
    }
    ;


    function _fnCargarSelectCatalogo(data, options) {
        $("#" + options.idSelectCatalogo).attr('disabled', true).find('option').remove();
        $("#" + options.idSelectCatalogo).append('<option value="">' + Generic.TEXT_SELECCIONAR + '</option>');

        var defParamasAjax = {
            dataType: 'json',
            type: "POST",
            url: options.urlGet,
            async: true,
            success: function (json) {
                _complete(data, options, json);
            },
            error: function (request, status, error) {
                base.mostrarMenajeErrorFormulario();
            }
        };

        var paramsAjax = {};
        if (options.configureAjax) {
            paramsAjax = options.configureAjax;
        }

        var finalParams = $.extend(defParamasAjax, paramsAjax);
        if (typeof finalParams.data === "undefined") {
            if (options.bPadre) {
                if (options.cOverridePadreKey != false) {
                    finalParams.data = {};
                    finalParams.data[options.cOverridePadreKey] = $("#" + options.idSelectPadre).val();
                } else {
                    finalParams.data = {};
                    finalParams.data[options.idSelectPadre] = $("#" + options.idSelectPadre).val();
                }
            }
        }

        $.ajax(finalParams);

    }
    ;

    function _complete(data, options, json) {
        $("#" + options.idSelectCatalogo).find('option').remove();
        if (options.agregarDefault) {
            var newOption = '<option value="' + options.valorDefault + '">' + options.EtiquetaSeleccione + '</option>';
            $("#" + options.idSelectCatalogo).append(newOption);
        }
        if (typeof json.data != "undefined") {
            for (i = 0; i < json.data.options.length; i++) {
                json.data.options[i].Text = CoreUtil.html.html_entity_decode(json.data.options[i].Text);
                var newOption = '<option value="' + json.data.options[i].ID + '"   >' + json.data.options[i].Text + '</option>';
                //var newOption = '<option value="' + json.data.options[i].ID + '"  ' + ((json.data.options[i].ID == data[options.idSelectCatalogo]) ? "selected=\"selected\"" : ""  ) + '  >' + json.data.options[i].Text + '</option>';
                $("#" + options.idSelectCatalogo).append(newOption);
            }
            if (options.cOverrideKey != false)
            {
                $("#" + options.idSelectCatalogo).val(data[options.cOverrideKey]).change();
            }
            else
            {
                $("#" + options.idSelectCatalogo).val(data[options.idSelectCatalogo]).change();
            }
        }
        $("#" + options.idSelectCatalogo).removeAttr("disabled");
        if (options.fnComplete) {
            options.fnComplete(options, json);
        }
    }

    // disparar eventos

    $("#" + options.idButtonMostrar).click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (typeof options.fnMostrarCatalogo == "function") {
            options.fnMostrarCatalogo(options);
        } else {
            _fnMostrarCatalogo(options);
        }
    });


    $("#" + options.idButtonCatalogoCancel).click(function (e) {
        e.preventDefault();

        if (typeof options.fnbtnCerrarModalCatalogo == "function") {
            options.fnbtnCerrarModalCatalogo(options);
        } else {
            _fnbtnCerrarModalCatalogo(options);
        }
    });

    $("#" + options.idButtonCatalogoAceptar).click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (typeof options.fnbtnCerrarModalCatalogo == "function") {
            options.fnBtnGuardarModalCatalogo(options);
        } else {
            _fnBtnGuardarModalCatalogo(options);
        }
    });

};



ClassCoreUI.prototype.PaginatorAjax = function (options) {

    // cargamos los defaults
    this.defaults = {
        "bDebug": false,
        "bAutoLoadAjax": true,
        "contentBody": "content-body-paginator",
        "contentPaginate": "content-paginate",
        "url": Generic.BASE_URL + Generic.CONTROLLER + "/getPagination/",
        "dataAjax": {},
        "fnAjaxSuccess": _fnAjaxSuccess,
        "fnCreateHeader":_fnCreateHeader,
        "fnCreateBody":_fnCreateBody,
        "fnCreatePaginate":_fnCreatePaginate,
        "fnAjaxError": base.mostrarErrorConexionServidor,
        "fnLoadAjax":this.fnLoadAjax
    };

    var _self = this;
    options = $.extend({}, _self.defaults, options);

    if (options.bAutoLoadAjax) {
        _self.loadAjax();
    }


    /**
     * Metodo que se encarga de realizar la petición ajax al servidor para recuperar los datos
     * @returns {undefined}
     */
    this.fnLoadAjax = function loadAjax(options) {
        
        var data = {};
        if(typeof options.dataAjax === "object"){
            data = options.dataAjax;
        }
        
        if(typeof options.dataAjax === "function"){
            data = options.dataAjax();
        }
        
        $.ajax({
            url:options.url,
            dataType:"json",
            data:data,
            success: function (oResponse) {
                options.fnAjaxSuccess(oResponse, options);
            },
            error:options.fnAjaxError
        });
    }
    
    
    function _fnAjaxSuccess(oResponse, options) {
        if (oResponse.success) {
            if (typeof options.fnCreateHeader === "function") {
                options.fnCreateHeader(oResponse, options);
            } 

            if (typeof options.fnCreateBody === "function") {
                options.fnCreateBody(oResponse, options);
            } 

            if (typeof options.fnCreatePaginate === "function") {
                options.fnCreatePaginate(oResponse, options);
            } 
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + "dashboard/index";
        }
    }
    
    
    function _fnCreateHeader(oResponse, options){
        
    }
    
    function _fnCreateBody(oResponse, options){
        
    }
    
    function _fnCreatePaginate(oResponse, options){
        
        
    }
    

};



ClassCoreUI.prototype.DataTable = function(){};
ClassCoreUI.prototype.DataTable.getRowAttr = function getRowAttr(cTabla, cAtributo) {
    var row = $("#" + cTabla).find("tr.selected");
    var valor = row.attr(cAtributo);
    return valor;
};

ClassCoreUI.prototype.DataTable.checkedAll = function checkedAll(element,cTabla, iEq, cHidden) {
    
    var oTable = $("#" + cTabla);
    var oCheckPadre = $(element);
    
    if(oCheckPadre.is(':checked'))
    {
        oTable.find("tr").find("td:eq("+ iEq +")").find("input[type=checkbox]").attr("checked","checked");
    }
    else
    {
        oTable.find("tr").find("td:eq("+ iEq +")").find("input[type=checkbox]").removeAttr("checked");
    }
    
    $("#" + cHidden).val("");
    
    return false;
};

ClassCoreUI.prototype.DataTable.deseleccionarCheck = function deseleccionarCheck(cCheckPadre,cTabla, iEq, cHidden) {
    
    var oTable = $("#" + cTabla);
    var oCheckPadre = $("#"+ cCheckPadre);
    var oHidden = $("#" + cHidden);
    
    oHidden.val('');
    oCheckPadre.removeAttr('checked');
    oTable.find("tr").find("td:eq("+ iEq +")").find("input[type=checkbox]").removeAttr("checked");
    
    return false;
};

/**
 * Metodo que se encarga de seleccionar o deseleccionar un input check del listado
 * y genera un string de ids en un campo hidden
 * @param {type} element
 * @param {type} cHidden
 * @returns {Boolean}
 */
ClassCoreUI.prototype.DataTable.checked = function checked(element, cHidden) {
    
    var oChecked = $(element);
    var valueCheck = oChecked.val();
    var aIds = CoreUI.DataTable.getArrChecked(cHidden);
    var iIndex = aIds.indexOf(valueCheck);
    var oHidden = $("#" + cHidden);
    var cIds = "";
    
    var aNuevoArray = [];
    
    // verificar si se quire quitar o agregar el valor
    if(oChecked.is(":checked"))
    {
        if(iIndex > -1)
        {
           delete aIds[iIndex]; 
        }
        
        aNuevoArray.push(valueCheck);
    }
    else
    {
        if(iIndex > -1)
        {
           delete aIds[iIndex]; 
        }
    }
    
    if (aIds.length > 0)
    {
        for (var i = 0; i < aIds.length; i++)
        {
            if ((typeof aIds[i] !== "undefined") && (aIds[i] != ""))
            {
                aNuevoArray.push(aIds[i]);
            }
        }
    }
    
    cIds = aNuevoArray.join(',');
    oHidden.val(cIds);
    
    return false;
};

ClassCoreUI.prototype.DataTable.getArrChecked = function getArrChecked(cHidden) {
    
    var cIds  = CoreUI.DataTable.getHiddenChecked(cHidden);
    
    if(cIds == "")
    {
        return [];
    }
    
    return cIds.split(",");
    
};

ClassCoreUI.prototype.DataTable.getHiddenChecked = function getHiddenChecked(cHidden) {
    var oHidden = $('#' + cHidden);
    return oHidden.val();
};


ClassCoreUI.prototype.DataTable.isChecked = function isChecked(oChecked,cHidden) {
    var cIds = CoreUI.DataTable.getHiddenChecked(cHidden);
    var valueCheck = oChecked.val();
    var aIds = CoreUI.DataTable.getArrChecked(cHidden);
    var iIndex = aIds.indexOf(valueCheck);
    
    return (iIndex > -1);
};

ClassCoreUI.prototype.validateTabs = function (cForma, paramsForma) {

    $('a[data-toggle="tab"]').on('shown', function (e) {
        _validarTabs(e, cForma, paramsForma);
    });

    function _validarTabs(e, cForma, paramsForma) {

        // objeto jquery del tab al cual le dio clic el usuario
        var oCurrentTab = $(e.target);

        // objeto jquery del tab anterior en donde se realizara las validaciones si no pasa la validacion no le permite al usuario cambiar de tab 
        var oRelatedTab = $(e.relatedTarget);

        // se recupera el nombre del tab anterior al que se realizan las validadciones
        var cHrefRelatedTab = (oRelatedTab.attr("href")).substr(1);

        // se recupera el nombre del tab actual 
        var cHrefCurrentTab = (oCurrentTab.attr("href")).substr(1);

        // listado de inputs que pertenecen al tab anterior
        var aInputs = $(".tab-content").find("div#" + cHrefRelatedTab).find(":input");

        // identificador html del input el cual se esta recorriendo para su validacion
        var id = "";

        // contador de inputs que tiene el tab a validar
        var iContaInputs = 0;

        // contador de inputs que pasaron la validacion
        var iContaValidate = 0;

        // se recoorren los inputs que pertenecen al tab
        $.each(aInputs, function (key, value) {
            id = $(value).attr("id");
            // se valida que si el input cuanta con una regla de validacion
            if (typeof paramsForma.rules[id] != "undefined") {
                // si el input cuenta con una regla de validacion sumamos uno
                iContaInputs++;
                bValidate = $("#" + cForma).validate().element($("#" + id));
                if (bValidate) {
                    // si el input pasa la regla de validacion sumamos uno
                    iContaValidate++;
                }
            }
        });

        // colocamos una bandera para saber si los inputs del tab pasaron todas las validaciones
        _bValidate = (iContaInputs > iContaValidate) ? false : true;

        if (_bValidate == false) {
            var aLista = $(".nav-tabs").find('li');
            var bContarCurrentLista = true;
            var iContaCurrentLista = 0;
            var iContaRelatedLista = 0;

            $.each(aLista, function (k, value) {
                oLink = $(value).find("a");
                cHrefLink = (oLink.attr("href")).substr(1);

                if (cHrefCurrentTab == cHrefLink) {
                    bContarCurrentLista = false;
                }

                if (bContarCurrentLista) {
                    iContaCurrentLista++;
                }

                if (cHrefRelatedTab == cHrefLink) {
                    bContarCurrentLista = false;
                }

                if (bContarCurrentLista) {
                    iContaRelatedLista++;
                }
            });

            if (iContaCurrentLista > iContaRelatedLista) {
                $(".nav-tabs").find('li').removeClass("active");
                $("div.tab-content").find('div').removeClass("active");

                $(".nav-tabs").find('li').find("a[href=#" + cHrefRelatedTab + "]").parent().addClass("active");
                $("div.tab-content").find('#' + cHrefRelatedTab).addClass("active");
            }

        }
    }
};

ClassCoreUI.prototype.FormaLlenarDatos = function (aDatos, cForma) {
    
    if(typeof aDatos === "null" || typeof aDatos === "undefined")
    {
        return false;
    }
    
    if(typeof cForma === "null" || typeof cForma === "undefined")
    {
        return false;
    }
        
    var aInputs = $("#" + cForma + " :input");
    $.each(aInputs, function(key, value){
        var cName = $(value).attr("name");
        var bNumberFormat = $(value).hasClass("numberFormat");
        if(typeof aDatos[cName] !== "undefined")
        {   
            if(bNumberFormat)
            {
                $(value).val(_CoreUI.format.formatNumber(aDatos[cName], "", 2));
            }
            else
            {
                if(aDatos[cName])
                {
                    $(value).val(aDatos[cName]);
                }
            }

        }
    });
};

var CoreUI = new ClassCoreUI();