/**
 * ClassImportacion
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de importacion
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 22-03-2015
 */
var ClassImportacion = (function () {
    
    this.iTipoImportacion;
    
    this.IMPORTACION_EXCEPCION;
    
    this.IMPORTACION_TIPO_CATALOGO;
    this.IMPORTACION_TIPO_EXCEPCION;
    
    this.TEXT_TABLA_COLUMNA = "";
    this.TEXT_TABLA_VALORES = "";
    this.TEXT_TABLA_MENSAGE = "";
    
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
        $("#forma-importacion").validate(oParams.paramsForma);
        _iniciarFileUpload();
    };
    
    this.initFormaExcepcion = function initFormaExcepcion(oParams)
    {
        $("#forma-importacion").validate(oParams.paramsForma);
        
        _iniciarFileUpload();
        
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
                {"sName": "cNombreTipoPeriodo", "sWidth": "10%"},
                {"sName": "idPeriodo" ,"sWidth": "10%"},
                {"sName": "cNombreMes" ,"bVisible": false}
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
                
                cActions += '<a role="button" class="btn btn-secondary show-tooltip" onclick="javascript:importacion.seleccionarPeriodo(\''+ aData[6] +'\', \'' +aData[1] +'\', \''+ aData[2] +'\' ,\''+ aData[3] +'\',\''+ aData[7] +'\');" ><span class="icon-arrow-down-4"></span>'+ Generic.TEXT_SELECCIONAR +'</a>';
                
                cActions += '';
                $('td:eq(6)', nRow).html(cActions);
                
                return nRow;
            }
        });
    };
    
    this.seleccionarPeriodo = function seleccionarPeriodo(idPeriodo, cNombre, dtFechaInicial, dtFechaFinal, cNombreMes)
    {
        $("#idPeriodo").val(idPeriodo);
        $("#cNombrePeriodo").val(cNombre);
        $("#cNombreMes").html(cNombreMes);
        $("#dtFechaInicial").html(dtFechaInicial);
        $("#dtFechaFinal").html(dtFechaFinal);
        
        $("#myModalPeriodos").modal('hide');
    };
    
    this.btnBorrarArchivo = function btnBorrarArchivo()
    {
        
    };

    this.btnGuardar = function btnGuardar() {
        var bValidation = $("#forma-" + Generic.CONTROLLER).valid();
        if (bValidation) {
            var oParams = {
                dataType: "json",
                resetForm: false,
                url: Generic.BASE_URL + Generic.CONTROLLER + "/importar",
                success: _guardarSuccess
            };
            $("#forma-" + Generic.CONTROLLER).ajaxForm(oParams);
            $("#forma-" + Generic.CONTROLLER).submit();
        } else {
            this.mostrarMenajeErrorFormulario();
        }
    };
    
    
    this.cambarImportacion = function cambarImportacion(element)
    {
        var oSelect = $(element);
        var idImportacion = oSelect.val();
        
        if(typeof idImportacion === "undefined" || idImportacion == "")
        {
            return false;
        }
        
        // si la importacion seleccionada en el campo select es la importacion de excepciones
        // se redirige al usuario a la forma de importacion de excepciones
        if(idImportacion == _self.IMPORTACION_EXCEPCION)
        {
            window.location.href = Generic.BASE_URL + "importacion/forma/" + _self.IMPORTACION_EXCEPCION;
            return false;
        }
        
        $.ajax({
            data: {
                "idImportacionDatos" : idImportacion,
            },
            url: Generic.BASE_URL  + "importacion/getImportacionAjax/",
            type: "POST",
            async: true,
            dataType:'json',
            success: function(oResponse) {
                
                if (oResponse.success) 
                {
                    $("#cArchivoDescargar").attr("href",oResponse.data.cUrlArchivo);
                }

                if (oResponse.failure) 
                {
                     _self.mostrarMensajesJSON(oResponse.data.messages);
                }

                if (oResponse.noLogin) 
                {
                    window.location.href = Generic.BASE_URL + 'dashboard/index';
                }
            },
            error: function() {
                _self.mostrarErrorConexionServidor();
            }
        });
    };
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Privados">
    
    function _guardarSuccess(oResponse){
        
        if(oResponse.success)
        {
            if(oResponse.data.errores.length > 0)
            {
                _mostrarTablaErrores(oResponse.data.errores);
            }
            else
            {
                $('#contendor-tabla-errores').hide('slow');
                _self.mostrarMensajesJSON(oResponse.data.messages);
            }
        }
        
        if(oResponse.failure)
        {
            _self.mostrarMensajesJSON(oResponse.data.messages);
        }
        
        if(oResponse.noLogin)
        {
            window.location.href = Generic.BASE_URL + 'dashboard/index';
        }
    }     
    
    function _mostrarTablaErrores(aErrores)
    {
        $('#contendor-tabla-errores').show('slow');
        
        var oContenedorTablaErrores = $(".contenedor-listado-errores");
        oContenedorTablaErrores.empty();
        
        var row;
        var columna;
        var valor;
        var mensaje;
        
        for(var i=0;i<aErrores.length;i++)
        {
            row =  $("<div class=\"row-fluid\"></div>");
            columna = $("<div class=\"span4\">"+ aErrores[i]["cColumnas"] +"</div>");
            valor = $("<div class=\"span4\">"+ aErrores[i]["cValores"] +"</div>");
            mensaje = $("<div class=\"span4\">"+ aErrores[i]["cMessage"] +"</div>");
            
            columna.appendTo(row);
            valor.appendTo(row);
            mensaje.appendTo(row);
            
            row.appendTo(oContenedorTablaErrores);
        }
    }
    
    function _iniciarFileUpload()
    {
        $("#fileupload").fileupload({
            dataType: 'json',
            url:Generic.BASE_URL + "importacion/cargarArchivoAjax/",
            start:function(){
                $('#contendor-tabla-errores').hide('slow');
            },
            done:function(e, data){
                
                var oResponse = data.result;
                if(oResponse.success)
                {
                   var aFile = oResponse.data.aFileData;
                   var tabla = CoreUI.tablaFileUpload(aFile);
                   
                   $("#tabla-fileupload").html(tabla);
                   $("#cNombreArchivo").val(aFile.file_name);
                }
                
                if(oResponse.failure)
                {
                    _self.mostrarMensajesJSON(oResponse.data.messages);
                }
                
                if(oResponse.noLogin)
                {
                    window.location.href = Generic.BASE_URL + "dashboard/index";
                }
            }
        });
    }
    
    // </editor-fold>
    
});

ClassImportacion.prototype = base;
var importacion = new ClassImportacion();


