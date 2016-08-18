<?php
// necesario para mostrar mensajes de error multi-idioma de codeigniter
$this->lang->load('form_validation');
?>

<?php script_tag("jquery"); ?>
<?php script_tag("jquery-ui"); ?>
<?php script_tag("bootstrap"); ?>

<?php incluye_componente_msgbox(); ?>
<?php incluye_componente_growl(); ?>

<?php script_tag("generic"); ?>
<?php script_tag("core-util"); ?>
<?php script_tag("core-ui"); ?>
<?php script_tag("base"); ?>

<script type="text/javascript">
    Generic.BASE_URL = '<?php echo base_url(); ?>';
    Generic.CONTROLLER = '<?php echo $this->uri->segment(1); ?>';
    Generic.ACTION = '<?php echo $this->uri->segment(2); ?>';
    Generic.AJAX_CONTROLLER = '<?php echo AJAX_CONTROLLER; ?>';

    Generic.TEXT_ACCION_CERRAR_SESION = '<?php echo lang("general_cerrar_session"); ?>';
    Generic.TEXT_ACCION_ACEPTAR = '<?php echo lang("general_accion_aceptar"); ?>';
    Generic.TEXT_ACCION_CANCELAR = '<?php echo lang("general_accion_cancelar"); ?>';
    Generic.TXT_MENSAHE_ERROR_FORMULARIO = '<?php echo lang("general_mensage_error_formulario"); ?>';
    Generic.TXT_ERROR_SUBIR_ARCHIVO = '<?php echo lang("general_error_subir_archivo"); ?>';
    Generic.TEXT_MENSAJE_NUEVO_USUARIO = '<?php echo lang("perfil_nuevo_usuario");  ?>';
    Generic.TEXT_MENSAJE_ERROR_CONEXION_SERVIDOR = '<?php  echo lang('general_error_conexion_servidor') ?>';
    
    Generic.TEXT_MENSAJE_ERROR_SELECCIONAR_REGISTRO = "<?php echo lang("general_error_mensaje_borrar_seleccionar_registro"); ?>";

    Generic.TEXT_TITULO_EXITO = '<?php echo lang("general_titulo_exito"); ?>';
    Generic.TEXT_TITULO_ALERTA = '<?php echo lang("general_titulo_alerta"); ?>';
    Generic.TEXT_TITULO_ERROR = '<?php echo lang("general_titulo_error"); ?>';
    Generic.TEXT_MENSAJE_ELIMINAR = '<?php echo lang("general_mensaje_eliminar"); ?>';

    Generic.TEXT_SELECCIONAR = '<?php echo lang("general_seleccionar"); ?>';
    Generic.TEXT_CARGANDO = '<?php echo lang("general_cargando"); ?>';
    Generic.DIRECTORIO_IMAGENES = '<?php echo DIRECTORIO_IMAGENES; ?>';
    Generic.DIRECTORIO_ICON = '<?php echo DIRECTORIO_IMAGENES_ICON; ?>';
    Generic.DIRECTORIO_SEPARADOR = '<?php echo DIRECTORIO_SEPARADOR; ?>';
    Generic.DIRECTORIO_JAVASCRIPT = '<?php echo DIRECTORIO_JAVASCRIPT; ?>';
    Generic.DIRECTORIO_HOJAS_ESTILO = '<?php echo DIRECTORIO_HOJAS_ESTILO; ?>';
    Generic.DIRECTORIO_TEMPORAL = '<?php echo DIRECTORIO_TEMPORAL; ?>';
    Generic.DIRECTORIO_ICONOS_16 = '';
    Generic.DIRECTORIO_ITEMS_MENU = '<?php echo DIRECTORIO_ITEMS_MENU; ?>';
    Generic.DIRECTORIO_MODULOS = "<?php echo DIRECTORIO_MODULOS; ?>";

    Generic.TABLE_SPROCESSING = '<?php echo lang('datatable_procesando'); ?>';
    Generic.TABLE_SZERORECORDS = '<?php echo lang('datatable_noregistrosencontrados'); ?>';
    Generic.TABLE_SHOW = '<?php echo lang('datatable_show'); ?>';
    Generic.TABLE_SINFOFILTERED = '<?php echo lang('datatable_infofiltrando'); ?>';
    Generic.TABLE_SSEARCH = '<?php echo lang('datatable_buscar'); ?>';
    Generic.TABLE_PAGINATE_SFIRST = '<?php echo lang('datatable_paginacion_primero'); ?>';
    Generic.TABLE_PAGINATE_SPREVIOUS = '<?php echo lang('datatable_paginacion_previo'); ?>';
    Generic.TABLE_PAGINATE_SNEXT = '<?php echo lang('datatable_paginacion_siguiente'); ?>';
    Generic.TABLE_PAGINATE_SLAST = '<?php echo lang('datatable_paginacion_ultimo'); ?>';
    Generic.TABLE_RECORDS = '<?php echo lang('datatable_lista_registros'); ?>';
    Generic.TABLE_A = '<?php echo lang('datatable_a'); ?>';
    Generic.TABLE_OF = '<?php echo lang('datatable_de'); ?>';
    Generic.TABLE_PRINT_MESSAGE = '<?php echo lang('datatable_mensajeimpresion'); ?>';
    Generic.TABLE_PRINT_DESCRIPTION = '<?php echo lang('datatable_descripcionimpresion'); ?>';
    Generic.TABLE_COPIADO = '<?php echo lang('datatable_copiado') ?>';
    Generic.TABLE_COPIADOS = '<?php echo lang('datatable_copiados') ?>';
    Generic.TABLE_REGISTRO = '<?php echo lang('datatable_registro') ?>';
    Generic.TABLE_REGISTROS = '<?php echo lang('datatable_registros') ?>';
    Generic.TABLE_EMPTY = '<?php echo lang('datatable_registrosnoencontrados') ?>';

    Generic.MESES = <?php echo lang('general_meses') ?>;
    Generic.DIAS = <?php echo lang('general_dias') ?>;

    Generic.MENU_LINK_DEFAULT = "<?php echo MENU_LINK_DEFAULT; ?>";
    Generic.IMAGEN_DEFAULT = "<?php echo getRutaImagenDefault(); ?>";

    Generic.VALIDADOR_TEXT_REQUIRED = "<?php echo lang("validate_required"); ?>";
    Generic.VALIDADOR_TEXT_REMOTE = "<?php echo lang("validate_remote"); ?>";
    Generic.VALIDADOR_TEXT_EMAIL = "<?php echo lang("validate_email"); ?>";
    Generic.VALIDADOR_TEXT_URL = "<?php echo lang("validate_url"); ?>";
    Generic.VALIDADOR_TEXT_DATE = "<?php echo lang("validate_date_jq"); ?>";
    Generic.VALIDADOR_TEXT_DATE_ISO = "<?php echo lang("validate_dateISO"); ?>";
    Generic.VALIDADOR_TEXT_NUMBER = "<?php echo lang("validate_number"); ?>";
    Generic.VALIDADOR_TEXT_DIGITS = "<?php echo lang("validate_digits"); ?>";
    Generic.VALIDADOR_TEXT_CREDIT_CARD = "<?php echo lang("validate_creditcard"); ?>";
    Generic.VALIDADOR_TEXT_EQUAL_TO = "<?php echo lang("validate_equalTo"); ?>";
    Generic.VALIDADOR_TEXT_EXTENSION = "<?php echo lang("validate_extension"); ?>";
    Generic.VALIDADOR_TEXT_MINLENGTH = "<?php echo lang("validate_minlength"); ?>";
    Generic.VALIDADOR_TEXT_MAXLENGTH = "<?php echo lang("validate_maxlength"); ?>";
    Generic.VALIDADOR_TEXT_RANGELENGTH = "<?php echo lang("validate_rangelength"); ?>";
    Generic.VALIDADOR_TEXT_RANGE = "<?php echo lang("validate_range"); ?>";
    Generic.VALIDADOR_TEXT_MIN = "<?php echo lang("validate_min"); ?>";
    Generic.VALIDADOR_TEXT_MAX = "<?php echo lang("validate_max"); ?>";
    Generic.VALIDADOR_TEXT_NIF_ES = "<?php echo lang("validate_nifES"); ?>";
    Generic.VALIDADOR_TEXT_NIE_ES = "<?php echo lang("validate_nieES"); ?>";
    Generic.VALIDADOR_TEXT_CIF_ES = "<?php echo lang("validate_cifES"); ?>";
    Generic.VALIDADOR_TEXT_MES_NO_VALIDO = "<?php echo lang("validate_mes_no_valido") ?>";
    
    Generic.VALIDADOR_TEXT_CURP = "<?php echo lang("validate_curp"); ?>";
    Generic.VALIDADOR_TEXT_LETTERS_ONLY = "<?php echo lang("validate_lettersonly"); ?>";
    Generic.VALIDADOR_TEXT_LETTERS_LOWEERCASE_ONLY = "<?php echo lang("validate_lettersonly"); ?>";
    Generic.VALIDADOR_TEXT_LETTERS_UNDERSCORE = "<?php echo lang("validate_lettersunderscore"); ?>";
    Generic.VALIDADOR_TEXT_LETTERS_UNDERSCORE_GUION = "<?php echo lang("validate_lettersunderscoreguion"); ?>";
    
    
    Generic.VALIDADOR_TEXT_IS_NATURAL_NO_CERO = "<?php echo lang("validate_is_natural_no_cero"); ?>";
    Generic.VALIDADOR_TEXT_INTEGER = "<?php echo lang("validate_integer"); ?>";
    Generic.VALIDADOR_TEXT_IS_NATURAL = "<?php echo lang("validate_is_natural"); ?>";
    
    Generic.VALIDADOR_TEXT_RFC_MORAL = "<?php echo lang("validate_rfc_moral_plugin"); ?>";
    Generic.VALIDADOR_TEXT_GREATER_THAN = "<?php echo lang("validate_greater_than"); ?>";
    Generic.VALIDADOR_TEXT_LESS_THAN = "<?php echo lang("validate_less_than"); ?>";
    Generic.VALIDADOR_TEXT_ALPHANUMERIC = "<?php echo lang("validate_alphanumeric"); ?>";
    
    Generic.VALIDADOR_NUMBER_SIN_GUION = "<?php echo lang("validate_number_sin_guion"); ?>";
    
    Generic.NUMBER_FORMAT = <?php echo NUMBER_FORMAT; ?>;
    
    Generic.VALIDADOR_TEXT_DATE_MENOR_A = "<?php echo lang("validate_date_menor_a"); ?>";
    
    Generic.VALIDADOR_NUMBER_SIN_GUION_NO_ZERO = "<?php echo lang("validate_number_sin_guion_no_zero"); ?>";
    Generic.VALIDADOR_TEXT_DIGITO_VERIFICADOR_INFONAVIT = "<?php echo lang("validate_digito_verificador_infonavit"); ?>";
     Generic.VALIDADOR_TEXT_RFC_FISICA = "<?php echo lang("validate_rfc_fisico_plugin"); ?>";
    
    
    Generic.SI = <?php echo SI; ?>;
    Generic.NO = <?php echo NO; ?>;
    
    Generic.TAG_SI = "<?php echo lang("general_si"); ?>";
    Generic.TAG_NO = "<?php echo lang("general_no"); ?>";
    
    Generic.CONTRATO_TREINTA_DIAS =<?php echo CONTRATO_TREINTA_DIAS;  ?>;
    Generic.CONTRATO_SESENTA_DIAS=<?php echo CONTRATO_SESENTA_DIAS;  ?>;
    Generic.CONTRATO_NOVENTA_DIAS=<?php echo CONTRATO_NOVENTA_DIAS;  ?>;
    Generic.CONTRATO_INDEFINIDO=<?php echo CONTRATO_INDEFINIDO;  ?>;
    
    Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA="<?php echo lang("general_error_seleccionar_tipo_nomina");  ?>";
    Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_EMPLEADO="<?php echo lang("general_error_seleccionar_tipo_nomina_empleado");  ?>";
    Generic.TEXT_ERROR_SELECCIONAR_REGISTRO="<?php echo lang("general_error_seleccionar_registro_listado");  ?>";
    
    Generic.TEXT_ERROR_SELECCIONAR_TIPO_NOMINA_PERIODO = "<?php echo lang("general_error_seleccionar_tipo_nomina_periodo");  ?>";
    Generic.TEXT_ERROR_ESTATUS_PERIODO = '<?php echo lang("general_error_estatus_periodo");  ?>';
    Generic.TEXT_ERROR_SELECCIONAR_EMPLEADO = "<?php echo lang("general_error_seleccionar_empleado");  ?>";
    Generic.TEXT_ERROR_SELECCIONAR_PERIODO_EMPLEADO = "<?php echo lang("general_error_seleccionar_periodo_empleado");  ?>";
    Generic.TEXT_ERROR_SELECCIONAR_NOMINA = "<?php echo lang("general_error_nomina_no_procesada"); ?>";
    
    Generic.TEXT_ERROR_EMPLEADO_ESTATUS_BAJA = "<?php echo lang("general_error_empleado_estatus_baja");  ?>";
    Generic.EMPLEADO_ESTATUS_BAJA = <?php echo EMPLEADO_ESTATUS_BAJA; ?>;
    Generic.EMPLEADO_ESTATUS_ALTA = <?php echo EMPLEADO_ESTATUS_ALTA; ?>;
    Generic.TEXT_CANCELAR = "<?php echo lang('general_accion_cancelar');  ?>";

    $(document).ready(function () {
        CoreUI.mostrarLoadingAJAX();
        CoreUI.mostrarMessageFlash(<?php echo showMessages(); ?>);
        CoreUI.selectMenu("<?php echo getController(); ?>");

        $('.show-tooltip').tooltip();
        $('.dropdown-menu').dropdown();
    });
</script>
