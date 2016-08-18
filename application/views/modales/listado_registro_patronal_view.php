<div id="myModalRegistroPatronal" class="modal-listado hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalRegistroPatronalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalRegistroPatronalLabel"><?php echo lang("registrospatronales_titulo"); ?></h3>
    </div>
    <div class="modal-body">

        <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
            <tr>
                <td>
                    <div class="ui-corner-all envoltura-tabla">
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-registropatronal">
                            <thead>
                                <tr>
                                    <th><?php echo lang('registrospatronales_codigo'); ?></th>
                                    <th><?php echo lang('registrospatronales_nombre'); ?></th>
                                    <th><?php echo lang('registrospatronales_empresa'); ?></th>
                                    <th><?php echo lang('general_acciones'); ?></th>
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
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo lang("general_accion_cancelar"); ?></button>
    </div>

</div>