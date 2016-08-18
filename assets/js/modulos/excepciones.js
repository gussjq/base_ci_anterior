/**
 * ClassExcepciones
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de excepciones
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 22-03-2015
 */
var ClassExcepciones = (function () {
    
    this.TEXT_ERROR_SELECCIONAR_PERIODO_TIPO_NOMINA = "";
    this.TEXT_ERROR_SELECCIONAR_EXCEPCION = "";
    this.TEXT_MENSAJE_INICIALIZAR = "";
    
    this.iTipoDiasAjuste = "";
    this.iTipoExcepcionDiasHoras = "";
    this.iTipoExcepcionMontos = "";
    
    this.iListado = "";
    
    var _bListadoPeriodos = false;
    var _bListadoEmpleados = false;
    var _bListadoConceptos = false;
    
    var _self = this;
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Listado">
    
    
    /**
     * Metodo que se encarga de inicializar los parametros de la forma
     * 
     * @access public
     * @param objeto json Parametros necesarios para inicializar la forma
     *        -paramsForma parametros de configuracion del plugin validationEngine para la forma de modulo_singular_minuscula
     * @returns void no torna datos
     */
    this.initForma = function initForma(oParams) {
        $("#forma-excepciones").validate(oParams.paramsForma);
    };
    
    /**
     * Metodo que se encarga de seleccionar un tipo de nomina o un empleado del listo
     * 
     * @param {int} iTipoListado
     * @param {string} cCodigo
     * @param {string} cNombre
     * @returns {undefined}
     */
    this.seleccionarTools= function seleccionarTools(){
        
        this.setTools();
        
        $("#idPeriodo").val("");
        $("#cNombrePeriodo").val("");
        
        oTablePeriodos.fnDraw();
        oTable.fnDraw();
    };
    
    this.btnAgregar = function btnAgregar(){
        var idPeriodo = $('#idPeriodo').val();
        
        var cUrl = Generic.BASE_URL + Generic.CONTROLLER + "/forma/";
        if(idPeriodo > 0)
        {
            cUrl += idPeriodo;
        }
        
        window.location.href = cUrl;
    };
    
    this.seleccionarPeriodo = function seleccionarPeriodo(idPeriodo, cNombre, listado){
        $("#idPeriodo").val(idPeriodo);
        $("#cNombrePeriodo").val(cNombre);
        
        if(listado)
        {
            oTable.fnDraw();
        }
        
        $("#myModalPeriodos").modal("hide");
    };
    
    this.selecccionarListado = function selecccionarListado(iTipo, id,cNombre){
        
        if (iTipo == 1) 
        {
            $("#idEmpleado").val(id);
            $("#cNombreEmpleado").val(cNombre);

            $("#myModalEmpleado").modal("hide");
        }

        if (iTipo == 2) 
        {
            $("#idConcepto").val(id);
            $("#cNombreConcepto").val(cNombre);

            $("#myModalConcepto").modal("hide");
        }
        
    };
    
    /**
     * Metodo que realiza la accion de guardar y actualizar
     * @returns {undefined}
     */
    this.btnGuardar = function btnGuardar() {
        var bValidation = $("#forma-excepciones").valid();
        if (bValidation) {
            var id = $("#forma-excepciones").find("#id").val();
            var cUrl = (id != "") ? Generic.BASE_URL + Generic.CONTROLLER + "/actualizar" : Generic.BASE_URL + Generic.CONTROLLER + "/insertar";
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: cUrl,
                success: _self.btnGuardarSuccess
            };
            $("#forma-excepciones").ajaxForm(oParams);
            $("#forma-excepciones").submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };
    
    /**
     * Metodo que se encarga de realizar la funcionalidad de la respuesta del servidor al momento de agregar o actualizar 
     * un dia de ajuste
     * 
     * @param {object} oResponse
     * @param {string} sStatus
     * @param {object} oXhr
     * @param {object} oForm
     * @returns {undefined}
     */
    this.btnGuardarSuccess = function btnGuardarSuccess(oResponse, sStatus, oXhr, oForm) {
        
        if (oResponse.success) {
            var idPeriodo = $("#idPeriodo").val();
            
            window.location.href = Generic.BASE_URL + Generic.CONTROLLER + "/listado/" + idPeriodo;
        }

        if (oResponse.failure) {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }

        if (oResponse.noLogin) {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    }
    
    this.btnCancelar = function btnCancelar(){
        var idTipoNomina = $("#idTipoNomina").val();
        var idPeriodo = $("#idPeriodo").val();
        
        window.location.href = Generic.BASE_URL + Generic.CONTROLLER + "/listado/" + idPeriodo;
    };
    
    this.btnEliminar = function btnEliminar(){
        
        var idPeriodo = $("#idPeriodo").val();
        var idExcepcion = CoreUI.DataTable.getRowAttr("listado", "data-idexcepcion");
        var idEmpleado = CoreUI.DataTable.getRowAttr("listado", "data-idempleado");
        var iTipoExcepcion = parseInt($("#iTipoExcepcion").val());
        
        if ((typeof idEmpleado == "undefined" || idEmpleado == "")
                || (typeof idPeriodo == "undefined" || idPeriodo == "") 
                || (typeof idExcepcion == "undefined" || idExcepcion == "")
                || (typeof iTipoExcepcion == "undefined" || iTipoExcepcion == "")) {
            
            CoreUI.mensajeMsgBox(_self.TEXT_ERROR_SELECCIONAR_EXCEPCION, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        var cUrl = "";
        switch(iTipoExcepcion){
            case _self.iTipoExcepcionDiasHoras:
                cUrl = Generic.BASE_URL + "excepcionesdiashoras/eliminar/";
                break;
            case _self.iTipoExcepcionMontos:
                cUrl = Generic.BASE_URL + "excepcionesmontos/eliminar/";
                break;
        }
        
        CoreUI.mensajeMsgBoxConfirm(Generic.TEXT_MENSAJE_ELIMINAR, function () {
            $.ajax({
                data: {"idEmpleado": idEmpleado, "idPeriodo": idPeriodo, "idExcepcion":idExcepcion},
                url: cUrl,
                type: "POST",
                async: false,
                dataType: 'json',
                success: function (oResponse) {
                    if (oResponse.success) {
                        oTable.fnDraw(false);
                        _self.mostrarMensajesJSON(oResponse.data.messages);
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
        });
    };
    
    this.btnInicializar = function btnInicializar(){
        var idPeriodo = $("#idPeriodo").val();
        var iTipoExcepcion = parseInt($("#iTipoExcepcion").val());
        var cNombrePeriodo = $("#cNombrePeriodo").val();
        
        if ((typeof idPeriodo == "undefined" || idPeriodo == "") 
                || (typeof iTipoExcepcion == "undefined" || iTipoExcepcion == "")) {
            CoreUI.mensajeMsgBox(_self.TEXT_ERROR_SELECCIONAR_EXCEPCION, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        cUrl = Generic.BASE_URL + "periodos/inicializarPeriodo/";
        CoreUI.mensajeMsgBoxConfirm(CoreUtil.str.sprintf(_self.TEXT_MENSAJE_INICIALIZAR, [cNombrePeriodo]), function () {
            $.ajax({
                data: { "idPeriodo": idPeriodo},
                url: cUrl,
                type: "POST",
                async: false,
                dataType: 'json',
                success: function (oResponse) {
                    if (oResponse.success) {
                        oTable.fnDraw(false);
                        _self.mostrarMensajesJSON(oResponse.data.messages);
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
        });
    };
    
    this.btnActualzar = function btnActualzar(){
        var idPeriodo = $("#idPeriodo").val();
        var iTipoExcepcion = parseInt($("#iTipoExcepcion").val());
        var idTipoNomina = $("#iTipoNominaTools").val();
        var idExcepcion = CoreUI.DataTable.getRowAttr("listado", "data-idexcepcion");
        
        if ((typeof idPeriodo == "undefined" || idPeriodo == "") 
                || (typeof iTipoExcepcion == "undefined" || iTipoExcepcion == "")
                || (typeof idTipoNomina == "undefined" || idTipoNomina == "")) {
            CoreUI.mensajeMsgBox(_self.TEXT_ERROR_SELECCIONAR_PERIODO_TIPO_NOMINA, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        if ((typeof idExcepcion == "undefined" || idExcepcion == "")) {
            CoreUI.mensajeMsgBox(_self.TEXT_ERROR_SELECCIONAR_EXCEPCION, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        var cUrl = "";
        switch(iTipoExcepcion){
            case _self.iTipoExcepcionDiasHoras:
                cUrl = Generic.BASE_URL + "excepcionesdiashoras/forma/" + idPeriodo + "/" + idExcepcion;
                break;
            case _self.iTipoExcepcionMontos:
                cUrl = Generic.BASE_URL + "excepcionesmontos/forma/"  + idPeriodo + "/" + idExcepcion;
                break;
        }
        
        window.location.href = cUrl;
    };
    
    this.btnExportar = function btnExportar(cUrl){
        window.open(cUrl, "_blank");
    };
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Forma">
    
    this.mostrarListadoEmpleados = function mostrarListadoEmpleados()
    {
        if(_bListadoEmpleados)
        {
            $("#listado-tabla-empleados tbody").empty();
             $("#myModalEmpleado").modal("show");
            oTableEmpleados.fnDraw();
        }
        else
        {
            _bListadoEmpleados = true;
            $("#myModalEmpleado").modal("show");
            _self.initListadoEmpleados();
        }
    };
    
    this.mostrarListadoConceptos = function mostrarListadoConceptos()
    {
        if(_bListadoConceptos)
        {
            $("#listado-tabla-concepto tbody").empty();
            $("#myModalConcepto").modal("show");
            oTableConceptos.fnDraw();
        }
        else
        {
            _bListadoConceptos = true;
            $("#myModalConcepto").modal("show");
            _self.initListadoConceptos();
        }
    };
    
    this.mostrarListadoPeriodos = function mostrarListadoPeriodos()
    {
        if(_bListadoPeriodos)
        {
            $("#listado-periodos tbody").empty();
            $("#myModalPeriodos").modal("show");
            
            oTablePeriodos.fnDraw();
        }
        else
        {
            _bListadoPeriodos = true;
            
            $("#myModalPeriodos").modal("show");
            _self.initListadoPeriodos();
        }
    };
    
    this.initListadoEmpleados = function initListadoEmpleados()
    {
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

                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:excepciones.selecccionarListado(1,' + aData[3] + ',  \'' + aData[0] + ' - ' + aData[1] + '\');" ><span class="icon-arrow-down-4"></span>' + Generic.TEXT_SELECCIONAR + '</a>';

                cActions += '';
                $('td:eq(3)', nRow).html(cActions);
                
                
                return nRow;
            }
        });
        
    };
    
    this.initListadoConceptos = function initListadoConceptos()
    {
        oTableConceptos = $('#listado-tabla-concepto').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 6,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "80px"},
                {"sName": "cNombre", "sWidth": "200px"},
                {"sName": "cNombreListado", "sWidth": "200px"},
                {"sName": "idConcepto", "sWidth": "100px"},
            ],
            "sAjaxSource": Generic.BASE_URL + "conceptos/listadoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "bHabilitado", value: 1};
                aoData[aoData.length] = {name: "iListadoConcepto", value: _self.iListado};
                dataTableConceptosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                var cActions = '';

                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:excepciones.selecccionarListado(2,' + aData[3] + ',  \'' + aData[0] +' - ' + aData[1] + '\');" ><span class="icon-arrow-down-4"></span>' + Generic.TEXT_SELECCIONAR + '</a>';

                cActions += '';
                $('td:eq(3)', nRow).html(cActions);
                return nRow;
            }
        });
    };
    
    this.initListadoPeriodos = function initListadoPeriodos()
    {
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
                {"sName": "idPeriodo" ,"sWidth": "10%"}
            ],
            "sAjaxSource": Generic.BASE_URL + "periodos/listadoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData[aoData.length] = {name: "idTipoNomina", value: $("#idTipoNomina").val()};
                aoData[aoData.length] = {name: "iAno", value: $("#iAno").val()};
                dataTablePeriodosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {

                $(nRow).attr("data-identificador", aData[9]);
                $(nRow).attr("data-nombre", aData[1]);
                $(nRow).attr("data-numero-periodo", aData[0]);
                
                var cActions = '';
                
                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:excepciones.seleccionarPeriodo(\''+ aData[6] +'\', \''+ aData[1] +'\');" ><span class="icon-arrow-down-4"></span>'+ Generic.TEXT_SELECCIONAR +'</a>';
                
                cActions += '';
                $('td:eq(6)', nRow).html(cActions);
                
                return nRow;
            }
        });
    };
    
    // </editor-fold>
    
});

ClassExcepciones.prototype = base;
var excepciones = new ClassExcepciones();