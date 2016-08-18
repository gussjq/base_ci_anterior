var ClassRoles = (function() {

    var _self = this;

    this.cNombre = "";
    this.cAlias = "";
    this.cDescripcion = "";
    this.aAcciones = [];

    this.init = function init() {

    };

    this.initForma = function initForma(oParams) {

        $("#forma-roles").validate(oParams.paramsForma);

        this.initAcciones();
    };

    this.btnBuscar = function btnBuscar(bNoEnviar) {
        this.cNombre = $("#cNombreBus").val();
        this.cAlias = $("#cAliasBus").val();

        if ((typeof bNoEnviar == "undefined") || (bNoEnviar === false)) {
            oTable.fnDraw(false);
        }

        return false;
    };

    /**
     * Metodo que se encarga de inizializar los check seleccionados por el rol
     * 
     * @access public
     * @returns void
     */
    this.initAcciones = function initAcciones() {
        var aCheckbox = $("#acciones").find("input[type=checkbox]");
        var cCheckID = "";
        var cAccionName = "";

        $.each(aCheckbox, function(ikey, oCheck) {
            cCheckID = $(oCheck).attr("id");
            if (_self.aAcciones.length > 0) {
                $.each(_self.aAcciones, function(iKeyAccion, oAccion) {
                    cAccionName = oAccion.cNombre + "_checkbox_" + oAccion.idAccion;
                    if (cCheckID == cAccionName) {
                        $(oCheck).attr("checked", "checked");
                    }
                });
            }
        });
        this.verificaCheckBox();
    };

    /**
     * 
     * @returns {undefined}
     */
    this.verificaCheckBox = function verificaCheckBox() {
        // recuperar los checkbox padres 
        var aCheckBoxPadres = $("#acciones").find(".padre_checkbox");
        var aCheckBoxTodos = $("#acciones").find(".checkbox");
        var oCheckBoxRoot = $("#acciones").find(".root_checkbox");
        var oCheckBoxPadre;
        var idModulo;
        var aCheckBoxHijos = [];
        var oCheckBoxHijo;
        var bTodos = true;
        var bTodosHijos = true;
        var oCheckBoxTodo;

        // recorremos todos los checkbox padre
        $.each(aCheckBoxPadres, function(iKeyPadre, checkBox) {
            oCheckBoxPadre = $(checkBox);
            idModulo = oCheckBoxPadre.attr("data-idModulo");
            aCheckBoxHijos = $("#acciones").find(".padre_" + idModulo);

            // se recorre todos los checkbox hijos
            oCheckBoxHijo = null;
            bTodosHijos = true;
            $.each(aCheckBoxHijos, function(iKeyHijo, checkBoxHijo) {
                oCheckBoxHijo = $(checkBoxHijo);
                // si alguno no esta seleccionado se cambia la bandera a false
                if (!oCheckBoxHijo.is(":checked")) {
                    bTodosHijos = false;
                }
            });

            // si todos los checkbox hijos estan seleccionados se selecciona el padre
            if ((bTodosHijos) && (aCheckBoxHijos.length > 0)) {
                oCheckBoxPadre.attr("checked", "checked");
            } else {
                oCheckBoxPadre.removeAttr("checked");
            }
        });

        // recorremos todos los checkbox tanto como padre he hijo para saber si estan seleccionados
        // para poder seleccionar el checbox root
        $.each(aCheckBoxTodos, function(ikeyTodos, checkBox) {
            oCheckBoxTodo = $(checkBox);
            if (!oCheckBoxTodo.is(":checked")) {
                bTodos = false;
            }
        });

        if ((bTodos) && (aCheckBoxTodos.length > 0)) {
            oCheckBoxRoot.attr("checked", "checked");
        } else {
            oCheckBoxRoot.removeAttr("checked");
        }
    };

    /**
     * 
     * @access public
     * @param object javascript checkBoxPadre
     * @returns void
     */
    this.checkPadre = function checkPadre(checkBoxPadre) {
        var oCheckBoxPadre = $(checkBoxPadre);
        var idPadre = oCheckBoxPadre.attr("id");
        var idModulo = oCheckBoxPadre.attr("data-idModulo");

        if (oCheckBoxPadre.is(":checked")) {
            $("#acciones").find(".padre_" + idModulo).attr("checked", "checked");
        } else {
            $("#acciones").find(".padre_" + idModulo).removeAttr("checked");
        }
        this.verificaCheckBox();
    };
    
    this.checkRoot = function checkRoot(checkRoot){
        var oCheckRoot = $(checkRoot);
        if (oCheckRoot.is(":checked")) {
            $("#acciones").find(".checkbox").attr("checked", "checked");
        } else {
            $("#acciones").find(".checkbox").removeAttr("checked");
        }
    }
});

ClassRoles.prototype = base;
var roles = new ClassRoles();