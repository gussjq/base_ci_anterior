<div id="myModalEmpresa" class="modal-listado hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalEmpresaLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalEmpresaLabel"><?php echo lang("empresas_titulo"); ?></h3>
    </div>
    <div class="modal-body">

        <table width="100%" cellpadding="2" cellspacing="1" class="data-grid">
            <tr>
                <td>
                    <div class="ui-corner-all envoltura-tabla">
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="listado-empresas">
                            <thead>
                                <tr>
                                    <th><?php echo lang('empresas_codigo'); ?></th>
                                    <th><?php echo lang('empresas_nombre'); ?></th>
                                    <th><?php echo lang('empresas_razon_social'); ?></th>
                                    <th><?php echo lang('empresas_rfc'); ?></th>
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