/**
 * ClassDashboard
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de dashboard
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 25-05-2015
 */
var ClassDashboard = (function () {
   
    this.TEXT_BTN_DETALLES = "";
    this.TEXT_NO_HAY_EMPLEADOS = "";
    
    var _self = this;
    var _bInitNuevos = false;
    var _bInitProceso = false;
    var _bInitTerminados = false;
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Publicos">
    
    /**
     * Metodo que se encarga de inicializar la funcionalidad de la pantalla Dashboard
     * @param {object} oParams
     * @returns {undefined}
     */
    this.initDashboard = function initDashboard()
    {   
        _self.initListadoNuevos();
        _self.initListadoTerminados();
    };
    
    /**
     * Metodo que se encarga de inicializar la funcionalidad del listado de pagos nuevos
     * @returns {undefined}
     */
    this.initListadoNuevos = function initListadoNuevos()
    {
        if(typeof oTablePagosNuevos !== "undefined")
        {
            if(_bInitNuevos)
            {
                $("#listado-pagos-nuevos tbody").empty();
                oTablePagosNuevos.fnDraw();
            }
            else
            {
                _bInitNuevos = true;
                _self.listadoNuevos();
            }
        }
    };
    
    /**
     * Metodo que se encarga de inicializar la funcionalidad del listado de pagos nuevos
     * @returns {undefined}
     */
    this.initListadoProceso = function initListadoProceso()
    {
        if(typeof oTablePagosEnProceso !== "undefined")
        {
            if(_bInitProceso)
            {
                $("#listado-pagos-en-proceso tbody").empty();
                oTablePagosEnProceso.fnDraw();
            }
            else
            {
                _bInitProceso = true;
                _self.listadoProceso();
            }
        }
    };
    
    /**
     * Metodo que se encarga de inicializar la funcionalidad del listado de pagos nuevos
     * @returns {undefined}
     */
    this.initListadoTerminados = function initListadoTerminados()
    {
        if(typeof oTablePagosTerminados !== "undefined")
        {
            if(_bInitTerminados)
            {
                $("#listado-pagos-terminados tbody").empty();
                oTablePagosTerminados.fnDraw();
            }
            else
            {
                _bInitTerminados = true;
                _self.listadoTerminados();
            }
        }
    };
    
    this.listadoNuevos = function listadoNuevos()
    {
        oTablePagosNuevos = $('#listado-pagos-nuevos').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "100px"},
                {"sName": "cNombre", "sWidth": "300px"},
                {"sName": "dtFechaInicial", "sWidth": "150px"},
                {"sName": "dtFechaFinal", "sWidth": "100px"},
                {"sName": "fTotalesPercepciones", "sWidth": "100px"},
                {"sName": "fTotalesDeducciones", "sWidth": "100px"},
                {"sName": "fTotalesNetoPagar", "sWidth": "100px"},
                {"sName": "iNumeroEmpleados", "sWidth": "100px"},
                {"sName": "idPeriodo", "sWidth": "100px"},
                {"sName": "idTipoNomina", "sWidth": "100px", "bVisible":false},
                
            ],
            "sAjaxSource": Generic.BASE_URL + "dashboard/listadoPeriodosNuevosAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
               
                dataTableNuevosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                
                var cActions = '<div class="fg-buttonset">';
                cActions += acciones["procesarPago"].replace("@@@@", aData[9]).replace("????", aData[8]) + "\n";
                cActions += '</div>';
                
                $("td:eq(8)", nRow).html(cActions);
                
                return nRow;
            }
        });
    };
    
    this.listadoProceso = function listadoProceso()
    {
        oTablePagosEnProceso = $('#listado-pagos-en-proceso').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "100px"},
                {"sName": "cNombre", "sWidth": "300px"},
                {"sName": "dtFechaInicial", "sWidth": "150px"},
                {"sName": "dtFechaFinal", "sWidth": "100px"},
                {"sName": "fTotalesPercepciones", "sWidth": "100px"},
                {"sName": "fTotalesDeducciones", "sWidth": "100px"},
                {"sName": "fTotalesNetoPagar", "sWidth": "100px"},
                {"sName": "iNumeroEmpleados", "sWidth": "100px"},
                {"sName": "idPeriodo", "sWidth": "100px"},
                {"sName": "idTipoNomina", "sWidth": "100px", "bVisible":false},
            ],
            "sAjaxSource": Generic.BASE_URL + "dashboard/listadoPeriodosProcesoAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
               
                dataTableProcesoUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                
                var cActions = '<div class="fg-buttonset">';
                cActions += acciones["procesarPago"].replace("@@@@", aData[9]).replace("????", aData[8]) + "\n";
                cActions += '</div>';
                
                $("td:eq(8)", nRow).html(cActions);
                
                return nRow;
            }
        });
    };
    
     this.listadoTerminados = function listadoTerminados()
    {
        oTablePagosTerminados = $('#listado-pagos-terminados').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 10,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "100px"},
                {"sName": "cNombre", "sWidth": "300px"},
                {"sName": "dtFechaInicial", "sWidth": "150px"},
                {"sName": "dtFechaFinal", "sWidth": "100px"},
                {"sName": "fTotalesPercepciones", "sWidth": "100px"},
                {"sName": "fTotalesDeducciones", "sWidth": "100px"},
                {"sName": "fTotalesNetoPagar", "sWidth": "100px"},
                {"sName": "iNumeroEmpleados", "sWidth": "100px"},
            ],
            "sAjaxSource": Generic.BASE_URL + "dashboard/listadoPeriodosTerminadosAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
               
                dataTableTerminadoUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                
                return nRow;
            }
        });
    };
    
   this.btnVisualizarAviso = function btnVisualizarAviso(idReceptor)
   {
       avisos.btnVisualizar(idReceptor);
       $("#aviso-" + idReceptor).attr("src", Generic.BASE_URL + Generic.DIRECTORIO_ICON + "aviso_leido.png");
   };
   
   /**
    * 
    * @returns {undefined}
    */
    this.btnBuscarEmpleado = function btnBuscarEmpleado() {
        var bValido = $("#forma-buscar-empleado").validate().element($("#cBuscarEmpleado"));
        if (bValido) 
        {
            _self.buscarEmpleadoAjax($("#cBuscarEmpleado").val(), Generic.BASE_URL + Generic.CONTROLLER + "/buscarEmpleados");
        }
    };
    
    this.buscarEmpleadoAjax = function buscarEmpleadoAjax(cBuscarEmpleado, cUrl)
    {
        $.ajax({
                url: cUrl,
                data: {
                    "cBuscarEmpleado": cBuscarEmpleado
                },
                type: "POST",
                dataType: 'json',
                success: function (oResponse) {

                    if (oResponse.success) {
                        _fnCreateBody(oResponse);
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
    
    /**
     * Configurar pago Ajax
     * @returns {Boolean}
     */
    this.configurarPagoAjax = function configurarPagoAjax(idTipoNomina, idPeriodo)
    {
        if((typeof idTipoNomina === "undefined" || idTipoNomina == "") 
            || (typeof idPeriodo === "undefined" || idPeriodo == ""))
        {
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_PERIODO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        $.ajax({
            data: {"idTipoNomina": idTipoNomina, "idPeriodo": idPeriodo},
            url: Generic.BASE_URL + "nomina/configurarPagoAjax",
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (oResponse) {
                
                if (oResponse.success) {
                    window.location.href=Generic.BASE_URL + "nomina/revisarEmpleados";
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
    
    /**
     * 
     * @returns {undefined}
     */
    this.irReciboNomina = function irReciboNomina(idTipoNomina, idPeriodo)
    {
        if((typeof idTipoNomina === "undefined" || idTipoNomina == "") 
            || (typeof idPeriodo === "undefined" || idPeriodo == ""))
        {
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_PERIODO, _self.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        window.location.href = Generic.BASE_URL +  "reciboNomina/listado/" +  idTipoNomina + "/" + idPeriodo;
    };
    
    /**
     * Metodo que se encarga de limpiar los datos del empleado 
     * @returns {undefined}
     */
    this.btnCancelarBusquedaEmpleado = function btnCancelarBusquedaEmpleado()
    {
        $("#cBuscarEmpleado").val("");
        $(".contenedor-listado-empleados").empty();
        $("#content-pie-paginator").empty();
        $(".contenedor-listado-empleados").html("<div class=\"row-fluid\"><div class=\"span12\"><strong class=\"row-title\"><a href=\"javascript:void(0);\"> "+ _self.TEXT_NO_HAY_EMPLEADOS + " </a></strong></div></div>");
    };
    
    this.formSubmit = function formSubmit(){
        return false;
    };
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    
    function _fnCreateBody(oResponse, options) 
    {
        var aEmpleados = oResponse.data.aEmpleados;
        var oContentBody = $("#content-body-paginator");
        var oContentPie = $("#content-pie-paginator");
        var oListado;

        oContentBody.empty();
        oContentPie.empty();
        if (aEmpleados.length > 0) {
            $.each(aEmpleados, function (key, value) {
                oListado = _crearListado(true, value);
                oListado.appendTo(oContentBody);
            });
            oContentPie.html(oResponse.data.cLink);
            _crearEventos();
        } else {
            oListado = _crearListado(false, {
                idEmpleado:0,
                cNombreCompleto:_self.TEXT_NO_HAY_EMPLEADOS
            });
            oListado.appendTo(oContentBody);
        }
    }
    
    function _crearEventos()
    {
        $("#paginacion li a").click(function (e) {
                e.preventDefault();
                cUrl = $(this).attr("href");
                $("#paginacion li").removeClass('active');
                $(this).parent().addClass('active');
                _self.buscarEmpleadoAjax($("#cBuscarEmpleado").val(), cUrl);
         });
    }
    
    function _crearListado(bPonerBoton, oEmpleado){
        
        // contenedor principal del row del empleado
        var oDivRow = $("<div></div>").attr("id", "row-empleado-" + oEmpleado.idEmpleado)
                .attr("class", "row-fluid");
        
        // nombre del empleado
        var oDivEmpleado = $("<div></div>").attr("id", "nombre-empleado" + oEmpleado.idEmpleado)
                .attr("class", "span9")
                .appendTo(oDivRow);
        
        var oStrong = $("<strong></strong>").attr("class", "row-title contenedor-listado-empleados")
                .appendTo(oDivEmpleado);
        
        var oLinkEmpleado = $("<a></a>").attr("href", "javascript:void(0);")
                .html(oEmpleado.cNombreCompleto)
                .appendTo(oStrong);
        
        // boton para ver el empleado
        var oDivBtn = $("<div></div>").attr("id", "boton-empleado" + oEmpleado.idEmpleado)
                .attr("class", "span3")
                .appendTo(oDivRow);
        
        if (bPonerBoton) {
            var btnVer = $("<button></button>").attr("class", "btn btn-secondary")
                    .attr("data-idEmpleado", oEmpleado.idEmpleado)
                    .text(_self.TEXT_BTN_DETALLES)
                    .appendTo(oDivBtn);

            btnVer.click(function (e) {
                e.preventDefault();
                _mostrarDetalleEmpleado(this);
            });
        }
        
        return oDivRow;
        
    }
    
    function _mostrarDetalleEmpleado(element){
        var oBotonEmpleado = $(element);
        var idEmpleado = oBotonEmpleado.attr("data-idEmpleado");
        
        if(idEmpleado>0){
            $.ajax({
                url: Generic.BASE_URL + Generic.CONTROLLER + "/detalleEmpleado",
                data: {
                    "idEmpleado": idEmpleado
                },
                type: "POST",
                dataType: 'json',
                success: function (oResponse) {

                    if (oResponse.success) {
                        _llenarDatos(oResponse.data.oEmpleado);
                        $("#my-modal-empleado-detalle").modal("show");
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
    }
    
    /**
     * 
     * @param {type} empleado
     * @returns {undefined}
     */
    function _llenarDatos(empleado){
        var cInput;
        var aEtiquetas = $("#my-modal-empleado-detalle").find(".campodetalle");
        $.each(aEtiquetas, function (key, value) {
            cInput = $(value).attr("data-nombre-input");
            if (typeof empleado[cInput] !== "undefined") {
                if (empleado[cInput]) {
                    $(value).html(empleado[cInput]);
                } else {
                    $(value).html("---");
                }
            } else {
                $(value).html("---");
            }
        });
    }
    
    // </editor-fold>

});

ClassDashboard.prototype = base;
var dashboard = new ClassDashboard();