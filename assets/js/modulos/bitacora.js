var ClassBitacora = (function() {

    var _this = this;

    this.idBitacora = '';
    this.idModulo = '';
    this.idAccion = '';
    this.idUsuario = '';
    this.cModulo = '';
    this.cAccion = '';
    this.dtFechaInicio = '';
    this.dtFechaFin = '';
    
    this.DayNames = [];
    this.MonthNames = [];
    this.dtFechaFinDayPicker = '';

    /**
     * Funcion que se encarga de inicializar la configuracion de la pantalla
     * @access public
     * @returns void
     */
    this.init = function init() {
        $('#dtFechaInicio').datepicker({
            yearRange: '1900:' + _this.dtFechaFinDayPicker,
            showOn: 'button',
            maxDate:"0",
            buttonImage: Generic.BASE_URL + Generic.DIRECTORIO_ICON + 'calendar.gif',
            dayNamesMin: _this.DayNames,
            monthNamesShort: _this.MonthNames,
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showAnim:'slideDown',
            monthNamesShort:Generic.MESES,
            dayNamesShort:Generic.DIAS
        });
        
        $('#dtFechaFin').datepicker({
            yearRange: '1900:' + _this.dtFechaFinDayPicker,
            showOn: 'button',
            maxDate:"0",
            buttonImage: Generic.BASE_URL + Generic.DIRECTORIO_ICON + 'calendar.gif',
            dayNamesMin: _this.DayNames,
            monthNamesShort: _this.MonthNames,
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showAnim:'slideDown',
            monthNamesShort:Generic.MESES,
            dayNamesShort:Generic.DIAS
        });

        $('#dtFechaInicio').datepicker({dateFormat: 'yy/mm/dd'});
        $('#dtFechaInicio').datepicker('setDate', new Date());
        $('#dtFechaFin').datepicker({dateFormat: 'yy/mm/dd'});
        $('#dtFechaFin').datepicker('setDate', new Date());
        
        
        CoreUI.MultiSelectAjax({
            url: Generic.BASE_URL + Generic.CONTROLLER + "/getAccionesAjax",
            idElementoBase: "#idModulo",
            idElementoActualizar: "#idAccion",
            valorDefault: "",
            agregarDefault: true,
        });
    };

    /**
     * Metodo encargado de realizar la funcionalidad de busqueda
     * @access public
     * @param boolean bNoEnviar
     * @returns boolean
     */
    this.btnBuscar = function btnBuscar() {

        this.idModulo = $("#idModulo").val();
        this.idAccion = $("#idAccion").val();
        this.idUsuario = $("#idUsuario").val();
        this.dtFechaInicio = $("#dtFechaInicio").val();
        this.dtFechaFin = $("#dtFechaFin").val();

        oTable.fnDraw();
        
        return false;
    };
    
    this.btnCancelarBusqueda = function btnCancelarBusqueda(){
        $("#forma-" + Generic.CONTROLLER + "-busqueda-avanzada").resetForm();
        
        var oDate = new Date();
        var cDate = oDate.getFullYear() + "-" + (CoreUtil.fecha.getMesNumero(oDate.getMonth())) + "-" + oDate.getDate();
        
        $("#dtFechaInicio").val(cDate);
        $("#dtFechaFin").val(cDate);
        
        this.btnBuscar();
    };    
    
});

ClassBitacora.prototype = base;
var bitacora = new ClassBitacora();

