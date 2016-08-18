/**
 * ClassAsociarClientes
 * 
 * Clase javascript encargada de la logica de la programación del lado del cliente del catálogo de asociarclientes
 * contiene los eventos y las peticiones ajax para realizar los procesos del catálogo 
 * 
 * @author DEVELOPER 1: <correo@developer1> cel: <1111111111>
 * @created: 23-04-2015
 */
var ClassAsociarClientes = (function () {
    
    this.idUsuario = "";
    this.idCliente = "";
    
    
    this.TEXT_TOOLTIP_SELECCIONE = "";
    this.TEXT_ERROR_SELECCIONAR = "";
    
    this.TEXT_NO_HAY_CLIENTES_SISTEMA = "";
    this.TEXT_NO_HAY_CLIENTES_ASOCIADOS = "";
    
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
        $("#forma-asociarclientes").validate(oParams.paramsForma);        
    };
    
    this.sortableStop = function sortableStop(event, ui) {

        var idCliente = 0;
        var aItemsClientes = [];
        var aClientesAsociados = [];
        var aItemsClientesAsociados = [];
        var oSortableClientes = $("ul#sortableClientes");
        var oSortableClientesAsociados = $("ul#sortableClientesAsociados");
        var aItems = oSortableClientesAsociados.find(".ui-state-default");
        
        $.each(aItems, function(){
            idCliente = $(this).attr("data-idCliente");
            aClientesAsociados.push(idCliente);
        });

        $.ajax({
            data: {
                "idUsuario":$("#idUsuario").val(),
                "aClientesAsociados":aClientesAsociados
            },
            url: Generic.BASE_URL + Generic.CONTROLLER + "/asociar/",
            type: "POST",
            async: true,
            dataType: 'json',
            success: function (oResponse) {
                if (oResponse.success) {
                    _self.mostrarMensajesJSON(oResponse.data.messages);
                    
                    aItemsClientes = oSortableClientes.find("li.ui-state-default");
                    aItemsClientesAsociados = oSortableClientesAsociados.find("li.ui-state-default");
                    oDeshabilitadoCliente = oSortableClientes.find("li.ui-desabilitar");
                    oDeshabilitadoClienteAsociado = oSortableClientesAsociados.find("li.ui-desabilitar");
                    
                    if(aItemsClientes.length > 0){
                        if(oDeshabilitadoCliente.length > 0){
                            oDeshabilitadoCliente.remove();
                        }
                    } else {
                        if(oDeshabilitadoCliente.length === 0){
                            oSortableClientes.empty();
                            $("<li class=\"ui-desabilitar\" >"+ _self.TEXT_NO_HAY_CLIENTES_SISTEMA +"<li>").appendTo(oSortableClientes);
                        }
                    }
                    
                    if(aItemsClientesAsociados.length > 0){
                        if(oDeshabilitadoClienteAsociado.length > 0){
                            oDeshabilitadoClienteAsociado.remove();
                        }
                    } else {
                        if(oDeshabilitadoClienteAsociado.length === 0){
                            oSortableClientesAsociados.empty();
                            $("<li class=\"ui-desabilitar\" >"+ _self.TEXT_NO_HAY_CLIENTES_ASOCIADOS +"<li>").appendTo(oSortableClientesAsociados);
                        }
                    }
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

    };
    
    this.btnRegresar = function btnRegresar(){
      window.location.href= Generic.BASE_URL + "usuarios/listado";  
    };
    
    // </editor-fold>
});

ClassAsociarClientes.prototype = base;
var asociarclientes = new ClassAsociarClientes();