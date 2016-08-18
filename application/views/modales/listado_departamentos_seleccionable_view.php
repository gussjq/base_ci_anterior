
<div id="myModalFiltroReciboDepartamento" class="modal modal-listado hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <input type="hidden" value ="" id="hiddenFiltroReciboDepartamentos" name="hiddenFiltroReciboDepartamentos" />
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo lang("departamentos_titulo"); ?></h3>
    </div>
    <div class="modal-body">
        <label id="hiddenFiltroReciboDepartamentos-error" class="error" for="hiddenFiltroReciboDepartamentos" style="display: none;"><?php echo lang('recibonomina_error_seleccionar_departamentos'); ?></label>
        <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
            <tr>
                <td>
                    <div class="ui-corner-all envoltura-tabla">
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-filtro-departamentos">
                            <thead>
                                <tr>
                                    <th><?php echo lang('empleados_numero'); ?></th>
                                    <th><?php echo lang('empleados_nombre'); ?></th>
                                    <th><?php echo form_checkbox("check-selecionar-todos", SI, FALSE, "id=\"check-selecionar-todos-empleados\" onclick=\"CoreUI.DataTable.checkedAll(this,'listado-filtro-departamentos', 2);\"");  ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>																	
                        </table>
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="recibonomina.btnCancelarListado(2);"><?php echo lang("general_accion_cancelar"); ?></button>
        <button type="button" class="btn btn-primary" onclick="recibonomina.btnSeleccionarListado(2);"><?php echo lang("general_accion_seleccionar"); ?></button>
    </div>
</div>
