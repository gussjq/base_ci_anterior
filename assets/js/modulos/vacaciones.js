/**
 * ClassVacaciones
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de vacaciones
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 14-08-2015
 */
var ClassVacaciones = (function () {

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
        
        $("#forma-vacaciones").validate(oParams.paramsForma);

        $('#dtFechaSalida').datepicker({
            yearRange: '1900:' + String(parseInt(this.dtFechaFinDayPicker) + 5),
            showOn: 'button',
            buttonImage: Generic.BASE_URL + Generic.DIRECTORIO_ICON + 'calendar.gif',
            dayNamesMin: this.DayNames,
            monthNamesShort: this.MonthNames,
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showAnim: 'slideDown',
            monthNamesShort:Generic.MESES,
                    dayNamesShort: Generic.DIAS,
        });

        $('#dtFechaLlegada').datepicker({
            yearRange: '1900:' + String(parseInt(this.dtFechaFinDayPicker) + 5),
            showOn: 'button',
            buttonImage: Generic.BASE_URL + Generic.DIRECTORIO_ICON + 'calendar.gif',
            dayNamesMin: this.DayNames,
            monthNamesShort: this.MonthNames,
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showAnim: 'slideDown',
            monthNamesShort:Generic.MESES,
                    dayNamesShort: Generic.DIAS,
        });

        oTableEmpleados = $('#listado-tabla-empleados').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 6,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "100px"},
                {"sName": "cNombreCompleto", "sWidth": "300px"},
                {"sName": "cNombreEstatus", "sWidth": "150px"},
                {"sName": "idEmpleado", "sWidth": "100px"},
            ],
            "sAjaxSource": Generic.BASE_URL + "empleados/listadoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "bHabilitado", value: 1};
                aoData[aoData.length] = {name: "idTipoNomina", value: $("#idTipoNomina").val()};
                dataTableEmpleadosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                var cActions = '';

                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:vacaciones.seleccionarListado(' + aData[3] + ',  \'' + aData[0] + ' - ' + aData[1] + '\', false);" ><span class="icon-arrow-down-4"></span>' + Generic.TEXT_SELECCIONAR + '</a>';

                cActions += '';
                $('td:eq(3)', nRow).html(cActions);

                
                return nRow;
            }
        });


        oTablePeriodos = $('#listado-periodos').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 6,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "5%"},
                {"sName": "cNombre", "sWidth": "25%"},
                {"sName": "dtFechaInicial", "sWidth": "10%"},
                {"sName": "dtFechaFinal", "sWidth": "10%"},
                {"sName": "dtFechaPago", "sWidth": "10%"},
//                {"sName": "cNombreEstatusPeriodo", "sWidth": "10%"},
                {"sName": "cNombreTipoPeriodo", "sWidth": "10%"},
                {"sName": "idPeriodo", "sWidth": "10%"}
            ],
            "sAjaxSource": Generic.BASE_URL + "periodos/listadoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "idTipoNomina", value: $("#idTipoNomina").val()};
                aoData[aoData.length] = {name: "iAno", value: vacaciones.dtFechaFinDayPicker};

                aoData[aoData.length] = {name: "bFiltroEstatus", value: 1};
                dataTablePeriodosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {

                $(nRow).attr("data-identificador", aData[9]);
                $(nRow).attr("data-nombre", aData[1]);
                $(nRow).attr("data-numero-periodo", aData[0]);

                var cActions = '';

                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:vacaciones.seleccionarPeriodo(' + aData[6] + ', \'' + aData[1] + '\');" ><span class="icon-arrow-down-4"></span>' + Generic.TEXT_SELECCIONAR + '</a>';

                cActions += '';
                $('td:eq(6)', nRow).html(cActions);

                return nRow;
            }
        });
    };
    
    /**
     * Metodo que se utiliza para seleccionar el tipo de nomina en la barra de acciones
     * en donde se cargan los empledos pertenecientes al tipo de nomina seleccionado
     * 
     * @returns {undefined}
     */
    this.seleccionarTools= function seleccionarTools(){
        this.setTools();
        
        $("#idEmpleado").val("");
        $("#cNombreEmpleado").val("");
        
        oTableEmpleados.fnDraw();
        oTable.fnDraw();
    };


    /**
     * Metodo que se encarga de mostrar empleados del listado en caso de que no este seleccionado un tipo de nomina
     * manda mensaje de error
     * 
     * @returns {Boolean}
     */
    this.mostrarListadoEmpleados = function mostrarListadoEmpleados() {

        if ($("#iTipoNominaTools").val() == "")
        {
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA, this.TIPO_MENSAJE_ERROR);
            return false;
        }

        $("#myModalEmpleado").modal("show");

    };

    /**
     * Metodo que se encarga de seleccionar el empleado del listado para filtrar el saldo de vacaciones
     *  
     * @param {int} identificador
     * @param {string} cNombre
     * @param {boolean} bRecargarTabla
     * @returns {undefined}
     */
    this.seleccionarListado = function seleccionarListado(identificador, cNombre, bRecargarTabla) {

        $("#idEmpleado").val(identificador);
        $("#cNombreEmpleado").val(cNombre);

        if (bRecargarTabla) {
            oTable.fnDraw();
        }
        _getVacacionesAjax(identificador, bRecargarTabla);

        $("#myModalEmpleado").modal("hide");
    };

    this.seleccionarPeriodo = function seleccionarPeriodo(identificador, cNombre) {

        $("#idPeriodo").val(identificador);
        $("#cNombrePeriodo").val(cNombre);

        $("#myModalPeriodos").modal("hide");
    };
    
    this.seleccionarTipoSaldo = function seleccionarTipoSaldo() {

         oTable.fnDraw();
    };
    
    this.calcularPrimaVacacional = function calcularPrimaVacacional(element){
        
        var oDiasPagar = $("#iDiasPagar");
        var bDiasPagarValido = $("#forma-vacaciones").validate().element(oDiasPagar);
        
        if(bDiasPagarValido == false){
            return false;
        }
        
        var iDias = oDiasPagar.val();
        oDiasPagar.val(CoreUtil.format.formatNumber(iDias, "", 2));
        
        _getPrimaVacacionalAjax();
    };
    
    /**
     * Metodo que sobre escribe la funcionalidad del boton de agregar en la clase base, ya que tiene que realizar validaciones adicionales
     * @returns {undefined}
     */
    this.btnAgregar = function btnAgregar(){
        var idEmpleado = $('#idEmpleado').val();
        var cUrl = Generic.BASE_URL + Generic.CONTROLLER + "/forma/";
        if(idEmpleado)
        {
            cUrl += idEmpleado;
        }
        
        window.location.href = cUrl;
    };
    
    /**
     * Metodo que sobre escribe la funcionalidad del boton de cancelar en la clase base, ya que tiene que realizar validaciones adicionales
     * @returns {undefined}
     */
    this.btnCancelar = function btnCancelar(){
        var idEmpleado = $('#idEmpleado').val();
        window.location.href = Generic.BASE_URL + Generic.CONTROLLER + "/listado/" + idEmpleado;
        
    };
    
    /**
     * Metodo que sobre escribe la funcionalidad de guardar
     * @param {type} idPadre
     * @returns {undefined}
     */
    this.btnGuardar = function btnGuardar(idPadre) {
        var bValidation = $("#forma-" + Generic.CONTROLLER).valid();
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
            _self.mostrarMenajeErrorFormulario();
        }
    };
    
    /**
     * Metodo que sobre escribe la funcionalidad de sucess al momento de guardar ya que es necesario agregar funcionalidad extra en el redireccionamiento
     * @param {type} oResponse
     * @param {type} sStatus
     * @param {type} oXhr
     * @param {type} oForm
     * @returns {undefined}
     */
    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        if (oResponse.success) {
            var idEmpleado = $("#idEmpleado").val();
            window.location.href = Generic.BASE_URL + Generic.CONTROLLER + "/listado/" + idEmpleado;
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    };
    
    this.menu = function menu(iTipoAccion){
        
        switch(iTipoAccion){
            case 1:
                _forma();
                break;
            case 2:
                _eliminar();
                break;
        };
    };

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    
    function _forma(){
        
        var idEmpleado = $("#idEmpleado").val();
        var idVacacion = CoreUI.DataTable.getRowAttr("listado", "data-idvacacion");
        
        if( (typeof idEmpleado === "undefined" || idEmpleado == "")){
        
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_EMPLEADO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        if((typeof idVacacion === "undefined" || idVacacion == "")){
            
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_REGISTRO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        window.location.href = Generic.BASE_URL + Generic.CONTROLLER + "/forma/"  + idEmpleado + "/" + idVacacion;
    }
    
    function _eliminar(){
        var idEmpleado = $("#idEmpleado").val();
        var idVacacion = CoreUI.DataTable.getRowAttr("listado", "data-idvacacion");
        var dtFechaSalida = CoreUI.DataTable.getRowAttr("listado", "data-dtfechasalida");
        
        if((typeof idEmpleado === "undefined" || idEmpleado == "")){
        
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_EMPLEADO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        if((typeof idVacacion === "undefined" || idVacacion == "")){
            
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_REGISTRO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        _self.btnEliminar(idVacacion, dtFechaSalida);
    };

    function _getVacacionesAjax(idEmpleado, bRecargarTabla) {
        if (typeof idEmpleado == "undefined"
                || idEmpleado == ""
                || idEmpleado == 0) {
            return;
        }

        $.ajax({
            data: {"idEmpleado": idEmpleado},
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getVacacionesAjax",
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (oResponse) {
                console.log(oResponse);
                if (oResponse.success) {

                    if (bRecargarTabla) {
                        if (typeof oResponse.data.oEmpleado !== "undefined") {
                            $("#lbl_cAntiguedad").html(oResponse.data.oEmpleado.cAntiguedad);
                            $("#lbl_cEstatusVacacion").html(oResponse.data.oEmpleado.cNombreEstatus + " ( " + oResponse.data.oEmpleado.dtFechaAntiguedad +" ) ");
                        } else {
                            $("#lbl_cAntiguedad").html("---");
                            $("#lbl_cEstatusVacacion").html("---");
                        }
                    } else {
                        if (typeof oResponse.data.oEmpleado !== "undefined") {
                            
                            $("#lbl_cNombreTablaPrestacion").html(oResponse.data.oEmpleado.cNombreTablaPrestacion);
                            $("#lbl_dtFechaAntiguedad").html(oResponse.data.oEmpleado.dtFechaAntiguedad);
                            $("#lbl_cAntiguedad").html(oResponse.data.oEmpleado.cAntiguedad);
                            $("#lbl_iAniversarios").html(CoreUtil.format.formatNumber(oResponse.data.oEmpleado.iAniversarios, "", 0));
                        } else {
                            $("#lbl_cNombreTablaPrestacion").html("---");
                            $("#lbl_dtFechaAntiguedad").html("---");
                            $("#lbl_cAntiguedad").html("---");
                            $("#lbl_iAniversarios").html("---");
                        }

                        if (typeof oResponse.data.oVacacion !== "undefined") {
                            $("#iDiasGozar").val(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasGozar, "", 2));
                            $("#iDiasPagar").val(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasPagar, "", 2));
                            $("#fPrimaPagar").val(CoreUtil.format.formatNumber(oResponse.data.oVacacion.fPrimaPagar, "", 2));
                            
                            $("#lbl_iDiasGozarAniversario").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasGozarAniversario, "", 2));
                            $("#lbl_iDiasGozarFecha").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasGozarFecha, "", 2));
                            $("#lbl_iDiasPagarAniversario").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasPagarAniversario, "", 2));
                            $("#lbl_iDiasPagarFecha").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.iDiasPagarFecha, "", 2));
                            $("#lbl_fPrimaPagarAniversario").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.fPrimaPagarAniversario, "", 2));
                            $("#lbl_fPrimaPagarFecha").html(CoreUtil.format.formatNumber(oResponse.data.oVacacion.fPrimaPagarFecha, "", 2));
                        } else {
                            $("#iDiasGozar").val(CoreUtil.format.formatNumber(0, "", 2));
                            $("#iDiasPagar").val(CoreUtil.format.formatNumber(0, "", 2));
                            $("#fPrimaPagar").val(CoreUtil.format.formatNumber(0, "", 2));
                            
                            $("#lbl_iDiasGozarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                            $("#lbl_iDiasGozarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                            $("#lbl_iDiasPagarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                            $("#lbl_iDiasPagarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                            $("#lbl_fPrimaPagarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                            $("#lbl_fPrimaPagarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                        }
                    }


                }

                if (oResponse.failure) {
                    _self.mostrarMensajesJSON(oResponse.data.messages);

                    if (bRecargarTabla) {
                        $("#lbl_cAntiguedad").html("---");
                        $("#lbl_cEstatusVacacion").html("---");
                    } else {
                        $("#lbl_cAntiguedad").html("---");
                        $("#lbl_iAniversarios").html("---");
                        $("#lbl_iDiasGozarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_iDiasGozarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_iDiasPagarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_iDiasPagarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_fPrimaPagarAniversario").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_fPrimaPagarFecha").html(CoreUtil.format.formatNumber(0, "", 2));
                        $("#lbl_cNombreTablaPrestacion").html("---");
                        $("#lbl_dtFechaAntiguedad").html("---");

                        $("#iDiasGozar").val(CoreUtil.format.formatNumber(0, "", 2));
                        $("#iDiasPagar").val(CoreUtil.format.formatNumber(0, "", 2));
                        $("#fPrimaPagar").val(CoreUtil.format.formatNumber(0, "", 2));
                    }

                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function () {
                _self.mostrarErrorConexionServidor();
            }
        });
    }
    
    /**
     * Funcion que se encarga de recuperar la prima vacacional correspondiente de acuerdo a los dias a pagar
     * @returns {undefined}
     */
    function _getPrimaVacacionalAjax(){
        
        var iDiasPagar = $("#iDiasPagar").val();
        var idEmpleado = $("#idEmpleado").val();
        
        $.ajax({
            data: {"idEmpleado": idEmpleado, "iDiasPagar":iDiasPagar},
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getPrimaVacacionalAjax",
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (oResponse) {
                
                if (oResponse.success) {
                    $("#fPrimaPagar").val(CoreUtil.format.formatNumber(oResponse.data.fPrimaPagar,"",2));
                }

                if (oResponse.failure) {
                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function () {
                _self.mostrarErrorConexionServidor();
            }
        });
    }

    // </editor-fold>

});

ClassVacaciones.prototype = base;
var vacaciones = new ClassVacaciones();