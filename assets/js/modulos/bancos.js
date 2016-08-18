/**
 * ClassBancos
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de bancos
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 14-12-2015
 */
var ClassBancos = (function () {
    
    this.idNomina;
    this.dtFechaPago = "";
    
    this.DayNames = [];
    this.MonthNames = [];
    this.dtFechaFinDayPicker = '';
    
    this.aBanamexCamposLayoutD = ["idCliente", "iSecuencial", "cDescripcion", "cCuentaCargo", "dtFechaPago"];
    this.aBanamexCamposLayoutC = ["idCliente", "iSecuencial", "cDescripcion", "cCuentaCargo", "dtFechaPago", "iSucursal"];
    this.aBanamexCamposLayoutB = ["idCliente", "iSecuencial", "cDescripcion", "cCuentaCargo", "dtFechaPago", "cNaturaleza", "cInstrucciones", "iSucursal"];
    this.aBanorteCamposLayoutNomina = ["iNumeroEmisora", "iSecuencial", "dtFechaPago"];
    this.aSatanderCamposLayoutNomina = ["cCuentaCargo", "dtFechaPago"];
    this.aBanregioCamposLayoutNomina = ["cCuentaCargo", "cCuentaAbono", "iInstitucion", "dtFechaPago"];
    
    this.RECIBONOMINA_FILTRO_TODOS = "";
    this.RECIBONOMINA_FILTRO_ACTIVO = "";
    this.RECIBONOMINA_FILTRO_LISTA = "";
    
    this.BANCOS_BANAMEX_LAYOUT_D = "";
    this.BANCOS_BANAMEX_LAYOUT_B = "";
    this.BANCOS_BANAMEX_LAYOUT_C_PAGOMATIC = "";
    this.BANCOS_BANORTE_LAYOUT_INTEGRADO_NOMINA = "";
    this.BANCOS_SATANDER_LAYOUT_NOMINA = "";
    this.BANCOS_BANREGIO_TRANSFERENCIAS_NACIONALES = "";
    
    var _self = this;
    var _bInitEmpleados = false;
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Publicos">
    
    /**
     * Metodo que se encarga de inicializar la forma de exportar layout a banco
     */
    this.initFormaExportarBanco = function initFormaExportarBanco(params)
    {
        $.validator.addMethod("validarLayout", _validarLayout, Generic.VALIDADOR_TEXT_REQUIRED);
        $("#forma-exportar-layout").validate(params.paramsForma);
        
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.AJAX_CONTROLLER + "/getBancosLayout",
            idElementoBase: "#idBanco",
            idElementoActualizar: "#idBancoLayout",
            valorDefault: "",
            agregarDefault: true
        });
        
        $('#dtFechaPago').datepicker({
            yearRange: '1900:' + (parseInt(this.dtFechaFinDayPicker) + 5),
            showOn: 'button',
            buttonImage: Generic.BASE_URL + Generic.DIRECTORIO_ICON + 'calendar.gif',
            dayNamesMin: this.DayNames,
            monthNamesShort: this.MonthNames,
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showAnim:'slideDown',
            monthNamesShort:Generic.MESES,
            dayNamesShort:Generic.DIAS
        });
    };
    
    /**
     * Metodo que se encarga de mostrar la forma modal en donde se selecciona el layout a exportar 
     * @returns {undefined}
     */
    this.modalExportarBanco = function modalExportarBanco()
    {
        var idNomina = $("#idNomina").val();
        var idPeriodo = $("#idPeriodo").val();
        var dtFechaPago = $("hiddenFechaPago").val();
        
        if (!idNomina || !idPeriodo)
        {
            CoreUI.mensajeMsgBox(Generic.TEXT_ERROR_SELECCIONAR_NOMINA, this.TIPO_MENSAJE_ERROR);
            return false;
        }
        
        if((typeof dtFechaPago != "undefined") && (dtFechaPago != ""))
        {
            $("#dtFechaPago").val(dtFechaPago);
        }
        
        _self.ocultarTodosLosCampos();
        
        $("#idNominaLayout").val(idNomina);
        $("#idPeriodoLayout").val(idPeriodo);
        $("#myModalExprtarLayoutBancos").modal('show');
    };
    
    /**
     * Metodo que se encarga de mostrar el listado de empleados para ser seleccionados para la exportacion del layout del banco
     * @returns {undefined}
     */
    this.mostrarListadoEmpleado = function mostrarListadoEmpleado(element)
    {
        var iFiltroEmpleadoBanco = $(element).val();
        if((iFiltroEmpleadoBanco == _self.RECIBONOMINA_FILTRO_TODOS) 
                || iFiltroEmpleadoBanco == _self.RECIBONOMINA_FILTRO_ACTIVO)
        {
            return false;
        }
       
       var idNomina = $("#idNomina").val();
       if(typeof idNomina != "undefined" && idNomina == "")
       {
           CoreUI.mensajeMsgBox(this.TEXT_ERROR_SELECCIONAR_NOMINA_NO_PROCESADA, this.TIPO_MENSAJE_ERROR);
           return false;
       }

        if(_bInitEmpleados)
        {
            $("#listado-filtro-bancos-empleados tbody").empty();
            setTimeout(function () {
                oTableEmpleadosBancos.fnDraw();
            }, 1000);     
        }
        else
        {
            _bInitEmpleados = true;
            _initListadoEmpleados();
        }
       
       $("#myModalExprtarLayoutBancos").modal('hide');
       $("#myModalFiltroBancoEmpleados").modal("show");
    };
    
    /**
     * Metodo que se encarga de cancelar el listado para seleccionar los empleados a los cuales se exportara en el 
     * layout a los bancos
     * @returns {undefined}
     */
    this.btnCancelarListadoEmpleado = function btnCancelarListadoEmpleado()
    {
        $("#checkFiltroBancoEmpleadosPadre").removeAttr("checked");
        $("#iFiltroEmpleadoBancos").val(1);
        $("#myModalExprtarLayoutBancos").modal('show');
        $("#hiddenFiltroBancoEmpleados").val("");
        $("#myModalFiltroBancoEmpleados").modal("hide");
    };
    
    /**
     * Metodo que se encarga de recuperar los ids de los empleados seleccionados y colocarlos en el 
     * campo hidden de la forma de exportar banco
     * @returns {undefined}
     */
    this.btnSeleccionarListadoEmpleado = function btnSeleccionarListadoEmpleado()
    {
        var oCheckboxPadre = $("#checkFiltroBancoEmpleadosPadre");
        var ids = $("#hiddenFiltroBancoEmpleados").val();

        if (!oCheckboxPadre.is(":checked"))
        {
            if (ids == "")
            {
                $("#hiddenFiltroBancoEmpleados-error").show();
            } 
            else
            {
                $("#hiddenFiltroBancoEmpleados-error").hide();
                $('#myModalFiltroBancoEmpleados').modal('hide');
                $('#myModalExprtarLayoutBancos').modal('show');
            }
        }
        else
        {
            $("#hiddenFiltroBancoEmpleados-error").hide();
            $('#myModalFiltroBancoEmpleados').modal('hide');
            $('#myModalExprtarLayoutBancos').modal('show');
        }
    };
    
    /**
     * Metodo que se encarga de cancelar el proceso de exportar el layout modal
     * @returns {undefined}
     */
    this.btnCancelarExportarLayoutModal = function btnCancelarExportarLayoutModal()
    {
        // se cancela la informacion de la fora de exportar a banco
       $("#hiddenFiltroEmpleadosBancos").val("");
       $("#bCheckTodosEmpleadosBancos").hide("");

       // se cancela la iformacion del listado de filtro de empleados 
       $("#hiddenFiltroBancoEmpleados").val("");
       $("#checkFiltroBancoEmpleadosPadre").removeAttr("checked");
       $('#myModalExprtarLayoutBancos').modal('hide');
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.btnExportarLayoutModal = function btnExportarLayoutModal()
    {  
        var bValidate = $("#forma-exportar-layout").valid();
        if (bValidate)
        {
            var idPeriodo = $("#idPeriodo").val();
            var idNomina = $("#idNomina").val();
            var idBanco = $("#idBanco").val();
            var idBancoLayout = $("#idBancoLayout").val();
            var hiddenFiltroEmpleados = $("#hiddenFiltroBancoEmpleados").val();
            var bCheckTodosEmpleadosBancos = ($("#checkFiltroBancoEmpleadosPadre").is(":checked")) ? 1 : 0;
            var idCliente = $("#idCliente").val();
            var iSecuencial = $("#iSecuencial").val();
            var cDescripcion = $("#cDescripcion").val();
            var cCuentaCargo = $("#cCuentaCargo").val();
            var dtFechaPago = $("#dtFechaPago").val();
            var cNaturaleza = $("#cNaturaleza").val();
            var cInstrucciones = $("#cInstrucciones").val();
            var iFiltroEmpleado = $("#iFiltroEmpleadoBancos").val();
            var iSucursal = $("#iSucursal").val();
            
            $.ajax({
            data: {
                "idPeriodoLayout" : idPeriodo,
                "idNominaLayout" : idNomina,
                "idBancoLayout" : idBancoLayout,
                "hiddenFiltroEmpleados":hiddenFiltroEmpleados,
                "bCheckTodosEmpleadosBancos":bCheckTodosEmpleadosBancos,
                "idCliente":idCliente,
                "iSecuencial":iSecuencial,
                "cDescripcion":cDescripcion,
                "cCuentaCargo":cCuentaCargo,
                "dtFechaPago":dtFechaPago,
                "cNaturaleza":cNaturaleza,
                "cInstrucciones":cInstrucciones,
                "iFiltroEmpleado":iFiltroEmpleado,
                "iSucursal":iSucursal,
                "idBanco":idBanco
            },
            url: Generic.BASE_URL + "bancos/validarExportarBancoAjax/",
            type: "POST",
            async: true,
            dataType:'json',
            success: function(oResponse) {
                
                if (oResponse.success) {
                    $("#bCheckTodosEmpleadosBancos").val(bCheckTodosEmpleadosBancos);
                    $("#hiddenFiltroEmpleadosBancos").val(hiddenFiltroEmpleados);
                    $("#idNominaLayout").val(idNomina);
                    $("#idPeriodoLayout").val(idPeriodo);
                    $("#forma-exportar-layout").submit();
                }

                if (oResponse.failure) {
                     _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {
                _self.mostrarErrorConexionServidor();
            }
        });
        }
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.ocultarTodosLosCampos = function ocultarTodosLosCampos()
    {
        $(".ocultar").hide('slow');
    };
    
    /**
     * 
     * @returns {undefined}
     */
    this.mostrarCampos = function mostrarCampos()
    {
        _self.ocultarTodosLosCampos();

        var idBancoLayout = $("#idBancoLayout").val();
        var aArray = _getArrayCampos(idBancoLayout);
        
        for(var i = 0; i < aArray.length; i++)
        {
            $('.' + aArray[i]).show('slow');
        }
    }
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    
    
    function _initListadoEmpleados()
    {
         oTableEmpleadosBancos = $('#listado-filtro-bancos-empleados').dataTable({
            "sDom": '<"top"lf<"clear">>rt<"bottom"ip<"clear">',
            "bSort": true,
            "bPaginate": true,
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "iDisplayLength": 6,
            "bServerSide": true,
            "bProcessing": true,
            "aoColumns": [
                {"sName": "iNumero", "sWidth": "100"},
                {"sName": "cNombreCompleto", "sWidth": "500"},
                {"sName": "idListadoEmpleado", "sWidth": "50", "bSortable":false},
            ],
            "sAjaxSource": Generic.BASE_URL + "ajax/listadoEmpleadoNominaAjax",
            "fnServerData": function (sSource, aoData, fnCallback) {
                
                aoData[aoData.length] = {name: "idNomina", value: $("#idNomina").val()};
                aoData[aoData.length] = {name: "idBanco", value: $("#idBanco").val()};
                aoData[aoData.length] = {name: "bFiltroBancos", value: 1};
                dataTableFiltroBancosEmpleadosUI.tablePipeLine(sSource, aoData, fnCallback);
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {

                $('td:eq(2)', nRow).html('');
                $(nRow).attr("data-idListadoEmpleado", aData[2]);

                var oCheckEmpleado = $("<input type= \"checkbox\" id=\"check-box-"+ aData[2] +"\" value=\""+ aData[2] +"\" style=\"text-align:center;\" onclick = \"CoreUI.DataTable.checked(this,'hiddenFiltroBancoEmpleados')\"/>");
                
                if($("#checkFiltroBancoEmpleadosPadre").is(":checked"))
                {
                    oCheckEmpleado.attr("checked", "checked");
                }
                else
                {
                    if(CoreUI.DataTable.isChecked(oCheckEmpleado, "hiddenFiltroBancoEmpleados"))
                    {
                        oCheckEmpleado.attr("checked", "checked");
                    }
                    else
                    {
                        oCheckEmpleado.removeAttr("checked");
                    }
                }
                
                oCheckEmpleado.appendTo($('td:eq(2)', nRow));

                return nRow;
            }
        });
    }
    
    /**
     * 
     * @param {type} cCampo
     * @returns {Boolean}
     */
    function _validarLayout(value, element, params)
    {
        var idBancoLayout = $("#idBancoLayout").val();    
        var aCampos = _getArrayCampos(idBancoLayout);
        var iIndexCampo = aCampos.indexOf(params[0]);
        
        if(iIndexCampo > -1)
        {
            return (value != "") ? true : false;
        }
        else
        {
            return true;
        }
    }
    
    function _getArrayCampos(idBancoLayout)
    {
        var aArray = [];
        switch(idBancoLayout)
        {
            case _self.BANCOS_BANAMEX_LAYOUT_B:
                aArray = _self.aBanamexCamposLayoutB;
                break;
            case _self.BANCOS_BANAMEX_LAYOUT_C_PAGOMATIC:
                aArray = _self.aBanamexCamposLayoutC;
                break;
            case _self.BANCOS_BANAMEX_LAYOUT_D:
                aArray = _self.aBanamexCamposLayoutD;
                break;
            case _self.BANCOS_BANORTE_LAYOUT_INTEGRADO_NOMINA:
                aArray = _self.aBanorteCamposLayoutNomina;
                break;    
            case _self.BANCOS_SATANDER_LAYOUT_NOMINA:
                aArray = _self.aSatanderCamposLayoutNomina;
                break;   
            case _self.BANCOS_BANREGIO_TRANSFERENCIAS_NACIONALES:
                aArray = _self.aBanregioCamposLayoutNomina;
                break;   
        }
        
        return aArray;
    }
    
    // </editor-fold>
    
});

ClassBancos.prototype = base;
var bancos = new ClassBancos();